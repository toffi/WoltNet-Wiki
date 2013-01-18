<?php
namespace wiki\system\comment\manager;

use wcf\system\comment\manager\AbstractCommentManager;
use wcf\system\WCF;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.comment.manager
 * @category 	WoltNet - Wiki
 */
class ArticleCommentManager extends AbstractCommentManager {

	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		if (WCF::getUser()->userID) {
			// TODO: check permissions
			$this->canAdd = true;
			$this->canDelete = true;
			$this->canEdit = true;
		}
	}

	/**
	 * @see wcf\system\comment\manager\AbstractCommentManager::canAdd()
	 */
	public function canAdd($objectID) {
		if (!$this->canAdd) {
			return false;
		}

		return true;
	}
	
	/**
	 * @see wcf\system\comment\manager\AbstractCommentManager::getLink()
	 */
	public function getLink($objectTypeID, $objectID) {
		return "";
	}
	
	/**
	 * @see wcf\system\comment\manager\AbstractCommentManager::getTitle()
	 */
	public function getTitle($objectTypeID, $objectID, $isResponse = false) {
		return "";
	}
	
	/**
	 * @see wcf\system\comment\manager\AbstractCommentManager::isAccessible()
	 */
	public function isAccessible($objectID) {
		return true;
	}
	
	/**
	 * @see wcf\system\comment\manager\AbstractCommentManager::updateCounter()
	 */
	public function updateCounter($objectID, $value) {
		
	}
}
