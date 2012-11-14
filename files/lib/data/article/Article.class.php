<?php
namespace wiki\data\article;
use wiki\data\WIKIDatabaseObject;
use wiki\data\category\WikiCategory;
use wiki\system\article\ArticlePermissionHandler;

use wcf\system\WCF;
use wcf\data\IUserContent;
use wcf\system\language\LanguageFactory;
use wcf\data\category\Category;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\request\IRouteController;
use wcf\system\bbcode\MessageParser;
use wcf\util\StringUtil;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\request\LinkHandler;
use wcf\system\cache\CacheHandler;

/**
 * @author	Ren� Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class Article extends WIKIDatabaseObject implements IRouteController, IUserContent {
	protected $category = null;
	protected $editor = null;
	protected $versionList = null;
	protected $author = null;
	protected $cache = null;
	protected $language = null;

	/**
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'article';

	/**
	* @see wcf\data\DatabaseObject::$databaseTableIndexName
	*/
	protected static $databaseTableIndexName = 'articleID';

	/**
	 * @see DatabaseObject::__construct()
	 */
	public function __construct($id, $row = null, $object = null) {
		//we need to overload the constructor for active row
		if ($id !== null) {
			$sql = "SELECT	*
					FROM	".static::getDatabaseTableName()."
					WHERE	(".static::getDatabaseTableIndexName()." = ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($id));
			$row = $statement->fetchArray();

			if ($row === false) $row = array();
		}

		parent::__construct(null, $row, $object);
	}

	public function getCategory() {
		if($this->category === null) {
			$category = new Category($this->categoryID);
			$this->category = new WikiCategory($category);
		}

		return $this->category;
	}

	public function getEditor() {
		if ($this->editor === null) {
			$this->editor = new ArticleEditor($this);
		}

		return $this->editor;
	}

	public function getVersions() {
		if($this->versionList === null) {
			$this->versionList = new ArticleList;
			$this->versionList->getConditionBuilder()->add("((article.parentID = ?) OR (article.articleID = ?) OR (article.parentID = ?) OR (article.articleID = ?))", array($this->parentID, $this->parentID, $this->articleID, $this->articleID));
			$this->versionList->readObjectIDs();
			$this->versionList = $this->versionList->getObjectIDs();

			foreach($this->versionList AS $key=>$value) {
				$this->versionList[$key] = ArticleCache::getInstance()->getArticle($value);
			}
		}

		return $this->versionList;
	}

	public function getAvailableLanguages($languageIDs) {
		$sql = "SELECT languageID FROM wiki".WIKI_N."_article
				WHERE translationID = ?
					AND languageID IN (?)";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(
				array(
					$this->translationID,
					implode(',', $languageIDs)
				));

		$languages = array();

		while($row = $statement->fetchArray()) {
			$languages[] = LanguageFactory::getInstance()->getLanguage($row['languageID']);
		}

		return $languages;
	}

	public function getArticleToLanguage($languageID) {
		if($this->languageID == $languageID) return $this;

		$sql = "SELECT articleID FROM wiki".WIKI_N."_article
				WHERE translationID  = ?
					AND languageID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array($this->translationID, $languageID));
		$article = null;
		while($row = $statement->fetchArray()) {
			$article = ArticleCache::getInstance()->getArticle($row['articleID']);
		}
		return $article->getActiveVersion();
	}

	public function getActiveVersion() {
		$versionList = array_merge($this->getVersions());

		$count = count($versionList)-1;
		if($count == 0) return $versionList[$count];
		foreach($versionList AS $versionListItem) {
			if($versionListItem->isActive && !$versionListItem->isDeleted) {
				return $versionListItem;
			}
		}
		return $versionList[$count];
	}

	public function getAuthor() {
		if ($this->author === null) {
			$this->author = new UserProfile(new User($this->userID));
		}

		return $this->author;
	}

	/**
	 * Returns an excerpt of this article.
	 *
	 * @param	string		$maxLength
	 * @return	string
	 */
	public function getExcerpt($maxLength = 255) {
		MessageParser::getInstance()->setOutputType('text/plain');
		$message = MessageParser::getInstance()->parse($this->message, false, false, true);
		if (StringUtil::length($message) > $maxLength) {
			$message = StringUtil::encodeHTML(StringUtil::substring($message, 0, $maxLength)).'&hellip;';
		}
		else {
			$message = StringUtil::encodeHTML($message);
		}

		return $message;
	}

	/**
	 * Checks the given article permissions.
	 * Throws a PermissionDeniedException if the active user doesn't have one of the given permissions.
	 *
	 * @param	array<string>		$permissions
	 */
	public function checkPermission(array $permissions = array('canViewArticle')) {
		foreach ($permissions as $permission) {
			if (!$this->getPermission($permission)) {
				throw new PermissionDeniedException();
			}
		}
	}

	/**
	 * Checks whether the active user has the permission with the given name on this article.
	 *
	 * @param	string		$permission	name of the requested permission
	 * @return	boolean
	 */
	public function getPermission($permission = 'canViewArticle') {
		return ArticlePermissionHandler::getInstance()->getPermission($this->articleID, $permission);
	}

	/**
	 * Checks whether the active user has the moderator permission with the given name on this article.
	 *
	 * @param	string		$permission	name of the requested permission
	 * @return	boolean
	 */
	public function getModeratorPermission($permission) {
		return ArticlePermissionHandler::getInstance()->getModeratorPermission($this->articleID, $permission);
	}

	/**
	 * Returns true, if the active user has the permission to enter this article.
	 *
	 * @return boolean
	 */
	public function canEnter() {
		if($this->isActive == 0) {
			return $this->getModeratorPermission('canReadDeactivatedArticle');
		}
		if($this->isDeleted == 1) {
			return $this->getModeratorPermission('canReadTrashedArticle');
		}
		return ($this->getPermission('canViewArticle') && $this->getPermission('canReadArticle'));
	}

	/**
	 * Returns true, if the active user has the permission to edit this article.
	 *
	 * @return boolean
	 */
	public function isEditable() {
		$ownPermission = false;
		$modPermission = false;
		if(WCF::getUser()->userID == $this->userID) {
			$ownPermission = $this->getPermission('canEditOwnArticle');
		}
		$modPermission = $this->getModeratorPermission('canEditArticle');

		return ($ownPermission || $modPermission);
	}

	/**
	 * Returns true, if the active user has the permission to trash this article.
	 *
	 * @return boolean
	 */
	public function isTrashable() {
		if($this->isDeleted == 1) return false;
		return $this->getModeratorPermission('canTrashArticle');
	}

	/**
	 * Returns true, if the active user has the permission to delete this article.
	 *
	 * @return boolean
	 */
	public function isDeletable() {
		return $this->getModeratorPermission('canDeleteArticle');
	}

	/**
	 * Returns true, if the active user has the permission to restore this article.
	 *
	 * @return boolean
	 */
	public function isRestorable() {
		if($this->isDeleted == 0) return false;
		return $this->getModeratorPermission('canRestoreArticle');
	}

	/**
	 * @see	wcf\system\request\IRouteController::getID()
	 */
	public function getID() {
		return $this->articleID;
	}

	/**
	 * @see	wcf\system\request\IRouteController::getTitle()
	 */
	public function getTitle() {
		return $this->subject;
	}

	/**
	 * @see wcf\data\IUserContent::getTime()
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 * @see wcf\data\IUserContent::getUserID()
	 */
	public function getUserID() {
		return $this->userID;
	}

	/**
	 * @see wcf\data\IUserContent::getUsername()
	 */
	public function getUsername() {
		return $this->username;
	}

	/**
	 * @see	wcf\data\ILinkableDatabaseObject::getLink()
	 */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('Article', array(
				'object' => $this
		));
	}

	public function getLanguage() {
		if($this->language === null) {
			$this->language = LanguageFactory::getInstance()->getLanguage($this->languageID);
		}
		return $this->language;
	}
}
