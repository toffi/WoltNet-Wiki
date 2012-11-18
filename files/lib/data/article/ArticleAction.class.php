<?php
namespace wiki\data\article;
use wiki\data\category\CategoryList;
use wiki\data\category\CategoryEditor;
use wiki\util\ArticleUtil;
use wiki\data\article\label\ArticleLabel;

use wcf\system\search\SearchIndexManager;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\user\storage\UserStorageHandler;
use wcf\data\IClipboardAction;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\exception\UserInputException;
use wcf\system\exception\ValidateActionException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\WCF;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class ArticleAction extends AbstractDatabaseObjectAction implements IClipboardAction {

	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wiki\data\article\ArticleEditor';

	/**
	 * list of active articles
	 * @var	array<wiki\data\article\Article>
	 */
	public $articles = array();

	/**
	 * @see DatabaseObjectEditor::create()
	 */
	public function create() {
		if(!isset($this->parameters['translationID'])) $this->parameters['translationID'] = ArticleUtil::getNextTranslationID();

		$object = call_user_func(array($this->className, 'create'), $this->parameters);

		// update search index
		SearchIndexManager::getInstance()->add('com.woltnet.wiki.article', $object->articleID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);

		return $object;
	}

	/**
	 * Validating parameters for trashing articles.
	 */
	public function validateTrash() {
		$this->loadArticles();

		foreach ($this->articles as $article) {
			if ($article->isDeleted) {
				throw new ValidateActionException("Action is not applicable for article ".$article->articleID);
			}

			if (!$article->isTrashable()) {
				throw new PermissionDeniedException();
			}
		}
	}

	/**
	 * Trashes given articles.
	 *
	 * @return	array<array>
	 */
	public function trash() {
		foreach ($this->articles as $article) {
			$articleEditor = new ArticleEditor($article);
			$articleEditor->update(array(
					'isDeleted' 	=> '1',
					'deleteTime'	=> TIME_NOW
			));
		}

		$this->unmarkItems();
	}
	
	/**
	 * Validates user access for label management.
	 */
	public function validateGetLabelManagement() {
		if (!WCF::getSession()->getPermission('user.wiki.article.read.canViewArticle') || !WCF::getSession()->getPermission('user.wiki.article.read.canReadArticle')) {
			throw new PermissionDeniedException();
		}
	}
	
	/**
	 * Returns the article label management.
	 *
	 * @return	array
	 */
	public function getLabelManagement() {
		WCF::getTPL()->assign(array(
				'cssClassNames' => ArticleLabel::getLabelCssClassNames(),
				'labelList' => ArticleLabel::getLabelsByUser()
		));
	
		return array(
				'actionName' => 'getLabelManagement',
				'template' => WCF::getTPL()->fetch('articleLabelManagement', 'wiki')
		);
	}

	/**
	 * Loads articles for given object ids.
	 */
	protected function loadArticles() {
		if (empty($this->objectIDs)) {
			throw new UserInputException("objectIDs");
		}

		// load articles
		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add("article.articleID IN (?)", array($this->objectIDs));
		$articleList->sqlLimit = 0;
		$articleList->readObjects();

		$categoryIDs = array();
		foreach ($articleList as $article) {
			$categoryIDs[] = $article->categoryID;
		}

		$this->articles = array();
		if (!empty($categoryIDs)) {
			$categoryList = new CategoryList();
			$categoryList->getConditionBuilder()->add("category.categoryID IN (?)", array($categoryIDs));
			$categoryList->sqlLimit = 0;
			$categoryList->readObjects();

			foreach ($articleList as $article) {
				foreach ($categoryList as $category) {
					if ($article->categoryID == $category->categoryID) {
						$article->setCategory($category);
						$this->articles[$article->articleID] = $article;
					}
				}
			}
		}

		if (empty($this->articles)) {
			throw new UserInputException("objectIDs");
		}
	}

	/**
	 * Marks projects as read.
	 */
	public function markAsRead() {
		if (empty($this->parameters['visitTime'])) {
			$this->parameters['visitTime'] = TIME_NOW;
		}

		if (!count($this->objects)) {
			$this->readObjects();
		}

		foreach ($this->objects as $article) {
			VisitTracker::getInstance()->trackObjectVisit('com.woltnet.wiki.article', $article->articleID, $this->parameters['visitTime']);
		}

		// reset storage
		if (WCF::getUser()->userID) {
			UserStorageHandler::getInstance()->reset(array(WCF::getUser()->userID), 'unreadArticles', PackageDependencyHandler::getInstance()->getPackageID('com.woltnet.wiki'));
		}
	}

	/**
	 * @see wcf\data\IClipboardAction::validateUnmarkAll()
	 */
	public function validateUnmarkAll() {
		// does nothing
	}

	/**
	 * @see wcf\data\IClipboardAction::unmarkAll()
	 */
	public function unmarkAll() {
		ClipboardHandler::getInstance()->removeItems(ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article'));
	}

	/**
	 * Unmarks projects.
	 */
	protected function unmarkItems() {
		ClipboardHandler::getInstance()->unmark(array_keys($this->articles), ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article'));
	}
}
