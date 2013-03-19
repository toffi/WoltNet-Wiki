<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategory;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\object\type\ObjectTypeCache;
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
	 * objectTypeName for Wiki Categories
	 *
	 * @var string
	 */
	public static $objectTypeName = 'com.woltnet.wiki.category';
	
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
	public function getPermission($permission = 'canViewCategory') {
		if ($this->permissions === null) {
			$this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
		}
		
		if (isset($this->permissions[$permission])) {
			return $this->permissions[$permission];
		}
		
		return WCF::getSession()->getPermission('user.wiki.category.read.'.$permission) || WCF::getSession()->getPermission('user.wiki.category.write.'.$permission) || WCF::getSession()->getPermission('user.wiki.article.read.'.$permission) || WCF::getSession()->getPermission('user.wiki.article.write.'.$permission);
	}
	
	/**
	 * Returns a list of accessible categories.
	 *
	 * @param	array		$permissions		filters categories by given permissions
	 * @return	array<integer>				comma separated category ids
	 */
	public static function getAccessibleCategoryIDs($permissions = array('canViewCategory', 'canEnterCategory')) {
		$categoryIDs = array();
		foreach (CategoryHandler::getInstance()->getCategories(static::$objectTypeName) as $category) {
			$result = true;
			$category = new WikiCategory($category);
			foreach ($permissions as $permission) {
				$result = $result && $category->getPermission($permission);
			}
		
			if ($result) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		
		return $categoryIDs;
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
	
	/**
	 * Returns true, if the given User has subscribed this category
	 *
	 * @param	integer	$userID
	 * @return	boolean
	 */
	public function isWatched($userID = 0) {
		$userID = ($userID > 0) ? $userID : WCF::getUser()->userID;
	
		$sql = "SELECT COUNT(*) AS count
				FROM wcf".WCF_N."_user_object_watch
				WHERE objectID = ?
					AND objectTypeID = ?
					AND userID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array(
				$this->categoryID,
				ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.user.objectWatch', 'com.woltnet.wiki.category')->objectTypeID,
				$userID));
		$row = $statement->fetchArray();
		if($row['count'] > 0) return true;
		return false;
	}
}
