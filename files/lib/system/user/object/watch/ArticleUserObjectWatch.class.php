<?php
namespace wiki\system\user\object\watch;

use wiki\data\article\WatchedArticle;
use wiki\data\article\Article;
use wiki\data\article\WatchedArticleList;
use wiki\data\category\WikiCategory;
use wcf\data\object\type\AbstractObjectTypeProcessor;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\object\watch\IUserObjectWatch;
use wcf\system\visitTracker\VisitTracker;

/**
 * User Watch object for articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.user.object.watch
 * @category WoltNet Wiki
 */
class ArticleUserObjectWatch extends AbstractObjectTypeProcessor implements IUserObjectWatch {
	/**
	 *
	 * @see wcf\system\user\object\watch\IUserObjectWatch::getUnreadCount()
	 */
	public function getUnreadCount($userID) {
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs();
		if(empty($categoryIDs))
			return null;
		
		$conditionBuilder = new PreparedStatementConditionBuilder();
		$conditionBuilder->add('user_object_watch.objectTypeID = ?', array (
				$this->objectTypeID 
		));
		$conditionBuilder->add('user_object_watch.userID = ?', array (
				$userID 
		));
		$conditionBuilder->add('article.categoryID IN (?)', array (
				$categoryIDs 
		));
		$conditionBuilder->add("article.isActive = '1'");
		$conditionBuilder->add('(article.time > tracked_article_visit.visitTime OR tracked_article_visit.visitTime IS NULL)');
		$conditionBuilder->add('(article.time > tracked_category_visit.visitTime OR tracked_category_visit.visitTime IS NULL)');
		
		$sql = "SELECT		COUNT(*) AS count
			FROM		wcf" . WCF_N . "_user_object_watch user_object_watch
			LEFT JOIN	wiki" . WCF_N . "_article article
			ON		(article.articleID = user_object_watch.objectID)
			LEFT JOIN 	wcf" . WCF_N . "_tracked_visit tracked_article_visit
			ON 		(tracked_article_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.article') . " AND tracked_article_visit.objectID = article.articleID AND tracked_article_visit.userID = " . $userID . ")
			LEFT JOIN 	wcf" . WCF_N . "_tracked_visit tracked_category_visit
			ON 		(tracked_category_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.category') . " AND tracked_category_visit.objectID = article.categoryID AND tracked_category_visit.userID = " . $userID . ")
			" . $conditionBuilder;
		
		return array (
				'sql' => $sql,
				'parameters' => $conditionBuilder->getParameters() 
		);
	}
	
	/**
	 *
	 * @see wcf\system\user\object\watch\IUserObjectWatch::getUnreadObjects()
	 */
	public function getUnreadObjects($userID, $limit = 5) {
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs();
		if(empty($categoryIDs))
			return null;
		
		$objectIDs = $this->getObjectIDs($userID);
		array_slice($objectIDs, 0, $limit);
		$objects = array ();
		foreach($objectIDs as $objectID) {
			$objects[] = new WatchedArticle(new Article($objectID));
		}
		return $objects;
	}
	
	/**
	 *
	 * @see wcf\system\user\object\watch\IUserObjectWatch::getObjectIDs()
	 */
	public function getObjectIDs($userID) {
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs();
		if(empty($categoryIDs))
			return null;
		
		$conditionBuilder = new PreparedStatementConditionBuilder();
		$conditionBuilder->add('user_object_watch.objectTypeID = ?', array (
				$this->objectTypeID 
		));
		$conditionBuilder->add('user_object_watch.userID = ?', array (
				$userID 
		));
		$conditionBuilder->add('article.categoryID IN (?)', array (
				$categoryIDs 
		));
		$conditionBuilder->add("article.isActive = '1'");
		
		$sql = "SELECT		user_object_watch.objectTypeID, user_object_watch.objectID,
					article.subject AS title, article.userID, article.username, article.time AS lastChangeTime
			FROM		wcf" . WCF_N . "_user_object_watch user_object_watch
			LEFT JOIN	wiki" . WCF_N . "_article article
			ON		(article.articleID = user_object_watch.objectID)
			" . $conditionBuilder;
		
		return array (
				'sql' => $sql,
				'parameters' => $conditionBuilder->getParameters() 
		);
	}
	
	/**
	 *
	 * @see wcf\system\user\object\watch\IUserObjectWatch::getObjects()
	 */
	public function getObjects(array $objectIDs) {
		$list = new WatchedArticleList();
		$list->setObjectIDs($objectIDs);
		$list->readObjects();
		return $list->getObjects();
	}
	
	/**
	 *
	 * @see \wcf\system\user\object\watch\IUserObjectWatch::resetUserStorage()
	 */
	public function resetUserStorage(array $userIDs) {
	}
	
	/**
	 *
	 * @see wcf\system\user\object\watch\IUserObjectWatch::validateObjectID()
	 */
	public function validateObjectID($objectID) {
		// get project
		$article = new Article($objectID);
		if(! $article->articleID)
			return false;
			
			// check permission
		return $article->getCategory()->checkPermission();
	}
}
