<?php
namespace wiki\data\article;

use wcf\system\WCF;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\visitTracker\VisitTracker;

/**
 * Represents a viewable article
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article
 * @category WoltNet - Wiki
 */
class ViewableArticle extends DatabaseObjectDecorator {

	/**
	 *
	 * @see wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wiki\data\article\Article';

	/**
	 * effective visit time
	 *
	 * @var integer
	 */
	protected $effectiveVisitTime = null;

	/**
	 * list of assigned labels
	 *
	 * @var array<wcf\data\label\Label>
	 */
	protected $labels = array ();

	/**
	 * Returns the user profile object.
	 *
	 * @return wcf\data\user\UserProfile
	 */
	public function getAuthor() {
		return $this->getActiveVersion()->getAuthor();
	}

	/**
	 * Alias for wiki\data\article\ViewableArticle::getAuthor()
	 *
	 * @return \wcf\data\user\UserProfile
	 */
	public function getUserProfile() {
		return $this->getAuthor();
	}

	/**
	 * Gets a specific article decorated as viewable article.
	 *
	 * @param integer $articleID        	
	 * @return wiki\data\article\ViewableArticleList
	 */
	public static function getViewableArticle($articleID) {
		$list = new ViewableArticleList();
		$list->getConditionBuilder()->add('article.articleID = ?', array (
				$articleID 
		));
		$list->readObjects();
		$objects = $list->getObjects();
		if(isset($objects[$articleID]))
			return $objects[$articleID];
		return null;
	}

	/**
	 * Returns the effective visit time.
	 *
	 * @return integer
	 */
	public function getVisitTime() {
		if($this->effectiveVisitTime === null) {
			if(WCF::getUser()->userID) {
				$this->effectiveVisitTime = max($this->visitTime, $this->categoryVisitTime, VisitTracker::getInstance()->getVisitTime('com.woltnet.wiki.article'));
			} else {
				$this->effectiveVisitTime = max(VisitTracker::getInstance()->getObjectVisitTime('com.woltnet.wiki.article', $this->articleID), VisitTracker::getInstance()->getObjectVisitTime('com.woltnet.wiki.category', $this->categoryID), VisitTracker::getInstance()->getVisitTime('com.woltnet.wiki.article'));
			}
			if($this->effectiveVisitTime === null) {
				$this->effectiveVisitTime = 0;
			}
		}
		
		return $this->effectiveVisitTime;
	}

	/**
	 * Returns true, if this article is new for the active user.
	 *
	 * @return boolean
	 */
	public function isNew() {
		if($this->getActiveVersion()->getTime() > $this->getVisitTime()) {
			return true;
		}
		
		return false;
	}

	/**
	 * Adds a label.
	 *
	 * @param wcf\data\label\Label $label        	
	 */
	public function addLabel(Label $label) {
		$this->labels[$label->labelID] = $label;
	}

	/**
	 * Returns a list of labels.
	 *
	 * @return array<wcf\data\label\Label>
	 */
	public function getLabels() {
		return $this->labels;
	}

	/**
	 * Returns true, if one or more labels are assigned to this issue.
	 *
	 * @return boolean
	 */
	public function hasLabels() {
		return (count($this->labels)) ? true : false;
	}
}
