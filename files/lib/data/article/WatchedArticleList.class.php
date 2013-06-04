<?php
namespace wiki\data\article;

use wiki\data\category\WikiCategory;
use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\WCF;

/**
 * Represents a list of watched articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.project
 * @category WoltNet Wiki
 */
class WatchedArticleList extends ViewableArticleList {

	/**
	 * Creates a new WatchedIssueList object.
	 */
	public function __construct() {
		parent::__construct();
		
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs();
		if(empty($categoryIDs)) {
			$this->getConditionBuilder()->add('1=0');
		} else {
			$objectTypeID = UserObjectWatchHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article');
			
			// add conditions
			$this->sqlConditionJoins .= "LEFT JOIN wiki" . WCF_N . "_article article ON (article.articleID = user_object_watch.objectID)";
			
			if(! empty($this->sqlSelects))
				$this->sqlSelects .= ',';
			$this->sqlSelects .= 'article_version.time AS time';
			$this->sqlConditionJoins .= "LEFT JOIN wiki" . WCF_N . "_article_version article_version ON article_version.versionID = article.activeVersionID";
			
			$this->getConditionBuilder()->add('article.activeVersionID IS NOT NULL');
			
			$this->getConditionBuilder()->add('user_object_watch.objectTypeID = ?', array (
					$objectTypeID 
			));
			$this->getConditionBuilder()->add('user_object_watch.userID = ?', array (
					WCF::getUser()->userID 
			));
			$this->getConditionBuilder()->add('article.categoryID IN (?)', array (
					$categoryIDs 
			));
			$this->getConditionBuilder()->add('article_version.isDeleted = 0');
		}
	}

	/**
	 *
	 * @see wcf\data\DatabaseObjectList::countObjects()
	 */
	public function countObjects() {
		$sql = "SELECT	COUNT(*) AS count
            FROM	wcf" . WCF_N . "_user_object_watch user_object_watch
            " . $this->sqlConditionJoins . "
            " . $this->getConditionBuilder()->__toString();
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($this->getConditionBuilder()->getParameters());
		$row = $statement->fetchArray();
		return $row['count'];
	}

	/**
	 *
	 * @see wcf\data\DatabaseObjectList::countObjects()
	 */
	public function readObjectIDs() {
		$this->objectIDs = array ();
		$sql = "SELECT	user_object_watch.objectID AS objectID
            FROM	wcf" . WCF_N . "_user_object_watch user_object_watch
                " . $this->sqlConditionJoins . "
                " . $this->getConditionBuilder()->__toString() . "
                " . (! empty($this->sqlOrderBy) ? "ORDER BY " . $this->sqlOrderBy : '');
		$statement = WCF::getDB()->prepareStatement($sql, $this->sqlLimit, $this->sqlOffset);
		$statement->execute($this->getConditionBuilder()->getParameters());
		while($row = $statement->fetchArray()) {
			$this->objectIDs[] = $row['objectID'];
		}
	}
}
