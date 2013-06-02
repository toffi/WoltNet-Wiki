<?php
namespace wiki\system\menu\page;

use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\menu\page\DefaultPageMenuItemProvider;
use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\visitTracker\VisitTracker;
use wcf\system\WCF;

/**
 * Shows the new article form.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.menu.page
 * @category	WoltNet Wiki
 */
class WatchedArticlesPageMenuItemProvider extends DefaultPageMenuItemProvider {
	/**
	 * number of unread watched bugs.
	 * @var	integer
	 */
	protected $notifications = null;

	/**
	 * @see	wcf\system\menu\page\IPageMenuItemProvider::isVisible()
	 */
	public function isVisible() {
		return (WCF::getUser()->userID ? true : false);
	}

	/**
	 * @see	wcf\system\menu\page\IPageMenuItemProvider::getNotifications()
	 */
	public function getNotifications() {
		if ($this->notifications === null) {
			$this->notifications = 0;
			
			if (WCF::getUser()->userID) {
				// load storage data
				UserStorageHandler::getInstance()->loadStorage(array(
						WCF::getUser()->userID 
				));
			}
		}
		
		return $this->notifications;
	}
}
