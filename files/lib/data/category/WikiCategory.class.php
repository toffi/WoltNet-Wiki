<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategory;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\user\User;
use wcf\system\WCF;

/**
 * Represents a wiki category.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategory extends ViewableCategory {
	
	/**
	 * Checks the given category permissions.
	 * Throws a PermissionDeniedException if the active user doesn't have one of the given permissions.
	 *
	 * @param	array<string>		$permissions
	 */
	public function checkPermission(array $permissions = array('canViewCategory')) {
		foreach ($permissions as $permission) {
			if (!$this->getPermission($permission)) {
				throw new PermissionDeniedException();
			}
		}
	}
	
	/**
	 * @see wcf\data\category\ViewableCategory::getPermission()
	 */
	public function getPermission($permission) {
		if ($this->permissions === null) {
			$this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
		}
		
		if (isset($this->permissions[$permission])) {
			return $this->permissions[$permission];
		}
		
		return WCF::getSession()->getPermission('user.wiki.category.read.'.$permission) || WCF::getSession()->getPermission('user.wiki.category.write.'.$permission) || WCF::getSession()->getPermission('user.wiki.article.read.'.$permission) || WCF::getSession()->getPermission('user.wiki.article.write.'.$permission);
	}
	
	/**
	 * Returns true if the category is accessible for the given user. If no
	 * user is given, the active user is used.
	 *
	 * @return    boolean
	 */
	public function isAccessible() {
		return $this->getPermission('canViewCategory') && $this->getPermission('canEnterCategory');
	}
}
