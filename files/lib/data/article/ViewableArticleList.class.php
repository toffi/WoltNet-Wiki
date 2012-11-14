<?php
namespace wiki\data\article;

use wcf\data\object\type\ObjectTypeCache;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class ViewableArticleList extends ArticleList {

	/**
	 * decorator class name
	 * @var string
	 */
	public $decoratorClassName = 'wiki\data\article\ViewableArticle';

	/**
	 * Creates a new ViewableProjectList object.
	 */
	public function __construct() {
		parent::__construct();

		if (WCF::getUser()->userID != 0) {
			// last visit time
			if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
			$this->sqlSelects .= 'tracked_visit.visitTime';
			$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_tracked_visit tracked_visit ON (tracked_visit.objectTypeID = ".VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.article')." AND tracked_visit.objectID = article.articleID AND tracked_visit.userID = ".WCF::getUser()->userID.")";

			if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
			$this->sqlSelects .= 'tracked_category_visit.visitTime AS categoryVisitTime';
			$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_tracked_visit tracked_category_visit ON (tracked_category_visit.objectTypeID = ".VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.category')." AND tracked_category_visit.objectID = article.categoryID AND tracked_category_visit.userID = ".WCF::getUser()->userID.")";

			// subscriptions
			//if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
			//$this->sqlSelects .= 'user_object_watch.watchID';
			//$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_user_object_watch user_object_watch ON (user_object_watch.objectTypeID = ".ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', 'com.woltnet.wiki.article')->objectTypeID." AND user_object_watch.userID = ".WCF::getUser()->userID." AND user_object_watch.objectID = article.articleID)";
		}
	}

	/**
	 * @see	wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if ($this->objectIDs === null) $this->readObjectIDs();
		parent::readObjects();

		foreach ($this->objects as $articleID => $article) {
			$this->objects[$articleID] = new $this->decoratorClassName($article);
		}
	}
}