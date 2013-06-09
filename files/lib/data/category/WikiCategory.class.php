<?php
namespace wiki\data\category;

use wcf\data\category\AbstractDecoratedCategory;
use wcf\system\category\CategoryPermissionHandler;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\label\group\LabelGroupList;
use wcf\data\label\group\ViewableLabelGroup;
use wcf\data\object\type\ObjectTypeCache;
use wcf\system\WCF;

/**
 * Represents a wiki category.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.category
 * @category WoltNet Wiki
 */
class WikiCategory extends AbstractDecoratedCategory {

	/**
	 * objectTypeName for Wiki Categories
	 *
	 * @var string
	 */
	public static $objectTypeName = 'com.woltnet.wiki.category';

	/**
	 * List of available labelGroups
	 *
	 * @var \wcf\data\label\group\LabelGroupList
	 */
	public $availableLabelGroups = null;

	/**
	 * Checks the given category permissions. Throws a PermissionDeniedException if the active user doesn't have one of the given permissions.
	 *
	 * @param array<string> $permissions        	
	 */
	public function checkPermission(array $permissions = array('canViewCategory')) {
		foreach($permissions as $permission) {
			if(! $this->getPermission($permission)) {
				throw new PermissionDeniedException();
			}
		}
	}

	/**
	 *
	 * @see wcf\data\category\ViewableCategory::getPermission()
	 */
	public function getPermission($permission = 'canViewCategory') {
		if($this->permissions === null) {
			$this->permissions = CategoryPermissionHandler::getInstance()->getPermissions($this->getDecoratedObject());
		}
		
		if(isset($this->permissions[$permission])) {
			return $this->permissions[$permission];
		}
		
		return WCF::getSession()->getPermission('user.wiki.category.read.' . $permission) || WCF::getSession()->getPermission('user.wiki.category.write.' . $permission) || WCF::getSession()->getPermission('user.wiki.article.read.' . $permission) || WCF::getSession()->getPermission('user.wiki.article.write.' . $permission);
	}

	/**
	 * Returns a list of accessible categories.
	 *
	 * @param array $permissions
	 *        	by given permissions
	 * @return array<integer> separated category ids
	 */
	public static function getAccessibleCategoryIDs($permissions = array('canViewCategory', 'canEnterCategory')) {
		$categoryIDs = array ();
		foreach(CategoryHandler::getInstance()->getCategories(static::$objectTypeName) as $category) {
			$result = true;
			$category = new WikiCategory($category);
			foreach($permissions as $permission) {
				$result = $result && $category->getPermission($permission);
			}
			
			if($result) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		
		return $categoryIDs;
	}

	/**
	 * Returns true if the category is accessible for the given user. If no user is given, the active user is used.
	 *
	 * @return boolean
	 */
	public function isAccessible() {
		return $this->getPermission('canViewCategory') && $this->getPermission('canEnterCategory');
	}

	/**
	 * Returns an array of all available LabelGroups
	 *
	 * @return array<wcf\data\label\group\ViewableLabelGroup>
	 */
	public function getAvailableLabelGroups() {
		if($this->availableLabelGroups === null) {
			// get object type
			$objectType = ObjectTypeCache::getInstance()->getObjectTypeByName('com.woltlab.wcf.label.objectType', 'com.woltnet.wiki.category');
			if($objectType === null) {
				return null;
			}
			$availableLabelGroups = new LabelGroupList();
			$availableLabelGroups->sqlJoins .= "LEFT JOIN wcf" . WCF_N . "_label_group_to_object label_group_to_object ON (label_group.groupID = label_group_to_object.groupID)";
			
			$availableLabelGroups->getConditionBuilder()->add("label_group_to_object.objectTypeID = ?", array (
					$objectType->objectTypeID 
			));
			$availableLabelGroups->getConditionBuilder()->add("label_group_to_object.objectID = ?", array (
					$this->categoryID 
			));
			
			$availableLabelGroups->readObjects();
			
			$this->availableLabelGroups = $availableLabelGroups->getObjects();
			
			foreach($this->availableLabelGroups as $key => $labelGroup) {
				$this->availableLabelGroups[$key] = new ViewableLabelGroup($labelGroup);
			}
		}
		
		return $this->availableLabelGroups;
	}
}
