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
}
