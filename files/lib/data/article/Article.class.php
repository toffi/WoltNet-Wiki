<?php
namespace wiki\data\article;
use wiki\data\WIKIDatabaseObject;
use wiki\data\category\WikiCategory;
use wiki\data\article\version\ArticleVersionList;

use wcf\system\WCF;
use wcf\data\IUserContent;
use wcf\data\IMessage;
use wcf\data\object\type\ObjectTypeCache;
use wcf\data\category\Category;
use wcf\system\language\LanguageFactory;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\data\ILinkableObject;
use wcf\system\comment\CommentHandler;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class Article extends WIKIDatabaseObject implements IRouteController, ILinkableObject, IUserContent, IMessage {
  protected $category = null;
  protected $editor = null;
  protected $versionList = null;
  protected $activeArticle = null;
  protected $author = null;
  protected $cache = null;
  protected $language = null;
  protected $commentList = null;

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

  /**
   * @see wcf\data\IMessage::getExcerpt()
   */
  public function getExcerpt($maxLength = 255, $highlight=false) {
      return $this->getActiveVersion($maxLength, $highlight);
  }

  /**
   * @see wcf\data\IMessage::getMessage()
   */
  public function getMessage() {
    return $this->getActiveVersion()->getMessage;
  }

  /**
   * @see	wcf\data\IMessage::__toString()
   */
  public function __toString() {
    return $this->getActiveVersion()->getFormattedMessage();
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
      $this->versionList = new ArticleVersionList;
      $this->versionList->getConditionBuilder()->add("(article_version.articleID = ?)", array($this->articleID));
      $this->versionList->readObjectIDs();
      $this->versionList = $this->versionList->getObjectIDs();

      $versionList = array();
      foreach($this->versionList AS $key=>$versionID) {
        $versionList[$versionID] = ArticleCache::getInstance()->getArticleVersion($versionID);
      }
      $this->versionList = $versionList;
    }

    return $this->versionList;
  }

  public function getAvailableLanguages($languageIDs) {
    $sql = "SELECT languageID FROM wiki".WCF_N."_article
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

    $sql = "SELECT articleID FROM wiki".WCF_N."_article
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

  /**
   * Gets the active Version of this article
   *
   * @return wiki\data\article\version\ArticleVersion
   */
  public function getActiveVersion() {
    if($this->activeArticle === null) {
        $versionList = $this->getVersions();
        if(array_key_exists($this->activeVersionID, $versionList)) {
            if($versionList[$this->activeVersionID]->isVisible())
                $this->activeArticle = $versionList[$this->activeVersionID];
        }
        if($this->activeArticle === null) {
            $this->activeArticle = end($versionList);
        }
    }
    return $this->activeArticle;
  }

  public function getVersion($versionID) {
    $versionList = $this->getVersions();
    if(array_key_exists($versionID, $versionList)) {
        return $versionList[$versionID];
    }
    return null;
  }

  /**
   * Alias for wiki\data\article\version\ArticleVersion::getAuthor()
   * @return \wcf\data\user\UserProfile
   */
  public function getUserProfile() {
      return $this->getActiveVersion()->getAuthor();
  }

  /**
   * Returns true, if the given User has subscribed this article
   *
   * @param	integer	$userID
   * @return	boolean
   */
  public function isWatched($userID = 0) {
    $userID = ($userID > 0) ? $userID : WCF::getUser()->userID;

    $sql = "SELECT COUNT(*) AS count
        FROM wcf".WCF_N."_user_object_watch
        WHERE objectID = ?
          AND objectTypeID = ?
          AND userID = ?";
    $statement = WCF::getDB()->prepareStatement($sql);
    $statement->execute(array(
          $this->articleID,
          ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', 'com.woltnet.wiki.article')->objectTypeID,
          $userID));
    $row = $statement->fetchArray();
    if($row['count'] > 0) return true;
    return false;
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
    return $this->getActiveVersion()->getTitle();
  }

  /**
   * @see wcf\data\IUserContent::getTime()
   */
  public function getTime() {
      return $this->getActiveVersion()->getTime();
  }

  /**
   * @see wcf\data\IUserContent::getUserID()
   */
  public function getUserID() {
    return $this->getActiveVersion()->getUserID();
  }

  /**
   * @see wcf\data\IUserContent::getUsername()
   */
  public function getUsername() {
    return $this->getActiveVersion()->getUsername();
  }

  /**
   * @see	wcf\data\ILinkableDatabaseObject::getLink()
   */
  public function getLink() {
    return LinkHandler::getInstance()->getLink('Article', array(
        'application' => 'wiki',
        'object' => $this
    ));
  }

  public function getLanguage() {
    if($this->language === null) {
      $this->language = LanguageFactory::getInstance()->getLanguage($this->languageID);
    }
    return $this->language;
  }

  public function getCommentList() {
      if($this->commentList === null) {
        $objectTypeID = CommentHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.articleComment');
      $objectType = CommentHandler::getInstance()->getObjectType($objectTypeID);
        $commentManager = $objectType->getProcessor();

        $this->commentList = CommentHandler::getInstance()->getCommentList($commentManager, $objectTypeID, $this->articleID);
      }
      return $this->commentList;
  }

    /**
     * @see wcf\data\IMessage::isVisible()
     */
    public function isVisible() {
        if($this->isActive == 0) {
            return $this->getActiveVersion()->getModeratorPermission('canReadDeactivatedArticle');
        }
        if($this->isDeleted == 1) {
            return $this->getActiveVersion()->getModeratorPermission('canReadTrashedArticle');
        }
        return $this->getActiveVersion()->getPermission('canViewArticle');
    }

    /**
     * @see wcf\data\IMessage::getFormattedMessage()
     */
    public function getFormattedMessage() {
          return $this->getActiveVersion()->getFormattedMessage();
    }
}
