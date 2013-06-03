<?php
namespace wiki\data\article;

use wiki\data\article\label\ArticleLabel;
use wiki\data\article\label\ArticleLabelList;
use wiki\system\label\object\ArticleLabelObjectHandler;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 *
 * @author Jean-Marc Licht
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article
 * @category WoltNet - Wiki
 */
class ViewableArticleList extends ArticleList {
	/**
	 * decorator class name
	 *
	 * @var string
	 */
	public $decoratorClassName = 'wiki\data\article\ViewableArticle';
	
	/**
	 * label list object
	 *
	 * @var wiki\data\article\label\ArticleLabelList
	 */
	public $labelList = null;
	
	/**
	 * Creates a new ViewableArticleList object.
	 */
	public function __construct() {
		parent::__construct();
		
		if(! empty($this->sqlSelects))
			$this->sqlSelects .= ',';
		$this->sqlSelects .= 'article_version.time AS time';
		$this->sqlJoins .= " LEFT JOIN wiki" . WCF_N . "_article_version article_version ON article_version.versionID = article.activeVersionID";
		
		if(WCF::getUser()->userID != 0) {
			// last visit time
			if(! empty($this->sqlSelects))
				$this->sqlSelects .= ',';
			$this->sqlSelects .= 'tracked_visit.visitTime';
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_tracked_visit tracked_visit ON (tracked_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.article') . " AND tracked_visit.objectID = article.articleID AND tracked_visit.userID = " . WCF::getUser()->userID . ")";
			
			if(! empty($this->sqlSelects))
				$this->sqlSelects .= ',';
			$this->sqlSelects .= 'tracked_category_visit.visitTime AS categoryVisitTime';
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_tracked_visit tracked_category_visit ON (tracked_category_visit.objectTypeID = " . VisitTracker::getInstance()->getObjectTypeID('com.woltnet.wiki.category') . " AND tracked_category_visit.objectID = article.categoryID AND tracked_category_visit.userID = " . WCF::getUser()->userID . ")";
			
			// subscriptions
			if(! empty($this->sqlSelects))
				$this->sqlSelects .= ',';
			$this->sqlSelects .= 'user_object_watch.watchID';
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_user_object_watch user_object_watch ON (user_object_watch.objectTypeID = " . ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', 'com.woltnet.wiki.article')->objectTypeID . " AND user_object_watch.userID = " . WCF::getUser()->userID . " AND user_object_watch.objectID = article.articleID)";
			
			// get avatar
			if(! empty($this->sqlSelects))
				$this->sqlSelects .= ',';
			$this->sqlSelects .= "user_avatar.*, user_table.email, user_table.disableAvatar, user_table.enableGravatar";
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_user user_table ON (user_table.userID = article_version.userID)";
			$this->sqlJoins .= " LEFT JOIN wcf" . WCF_N . "_user_avatar user_avatar ON (user_avatar.avatarID = user_table.avatarID)";
		}
	}
	
	/**
	 *
	 * @see wcf\data\DatabaseObjectList::readObjects()
	 */
	public function readObjects() {
		if($this->objectIDs === null)
			$this->readObjectIDs();
		parent::readObjects();
		
		// get assigned labels
		$articleIDs = array ();
		foreach($this->objects as $article) {
			if($article->hasLabels) {
				$articleIDs[] = $article->articleID;
			}
		}
		
		if(! empty($articleIDs)) {
			$assignedLabels = ArticleLabelObjectHandler::getInstance()->getAssignedLabels($articleIDs);
			foreach($assignedLabels as $articleID => $labels) {
				foreach($labels as $label) {
					$this->objects[$articleID]->addLabel($label);
				}
			}
		}
	}
	
	/**
	 * Returns a list of article labels.
	 *
	 * @return array<wiki\data\article\label\ArticleLabel>
	 */
	protected function getLabels() {
		if($this->labelList === null) {
			$this->labelList = ArticleLabel::getLabelsByCategory();
		}
		
		if(is_object($this->labelList))
			return $this->labelList->getObjects();
		
		return null;
	}
	
	/**
	 * Returns label assignments per article.
	 *
	 * @return array<array>
	 */
	protected function loadLabelAssignments() {
		$labels = $this->getLabels();
		if(empty($labels)) {
			return array ();
		}
		
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("articleID IN (?)", array (
				array_keys($this->objects) 
		));
		$conditions->add("labelID IN (?)", array (
				array_keys($labels) 
		));
		
		$sql = "SELECT	labelID, articleID
            FROM	wiki" . WCF_N . "_article_label_to_object
            " . $conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		$data = array ();
		while($row = $statement->fetchArray()) {
			if(! isset($data[$row['articleID']])) {
				$data[$row['articleID']] = array ();
			}
			
			$data[$row['articleID']][$row['labelID']] = $labels[$row['labelID']];
		}
		
		return $data;
	}
	
	/**
	 *
	 * @param wiki\data\article\label\ArticleLabelList $labelList        	
	 */
	public function setLabelList(ArticleLabelList $labelList) {
		$this->labelList = $labelList;
	}
}