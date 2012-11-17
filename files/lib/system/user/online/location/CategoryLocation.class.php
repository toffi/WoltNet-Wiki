<?php
namespace wiki\system\user\online\location;

use wcf\system\category\CategoryHandler;
use wcf\data\user\online\UserOnline;
use wcf\system\user\online\location\IUserOnlineLocation;
use wcf\system\WCF;

/**
 * Implementation of IUserOnlineLocation for the category page location.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.user.online.location
 * @category 	WoltNet Wiki
 */
class CategoryLocation implements IUserOnlineLocation {
	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::cache()
	 */
	public function cache(UserOnline $user) {}

	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::get()
	 */
	public function get(UserOnline $user, $languageVariable = '') {
		if ($category = CategoryHandler::getInstance()->getCategory($user->objectID)) {
			if ($category->getPermission()) {
				return WCF::getLanguage()->getDynamicVariable($languageVariable, array('category' => $category));
			}
		}

		return '';
	}
}