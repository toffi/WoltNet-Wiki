<?php
namespace wiki\data\category;
use wiki\data\WIKIDatabaseObject;

use wcf\system\request\IRouteController;
use wcf\system\breadcrumb\IBreadcrumbProvider;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * Manages the category cache.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet - Wiki
 */
class Category extends WIKIDatabaseObject implements IBreadcrumbProvider, IRouteController {
	/**
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'category';
	
	/**
	 * @see wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'categoryID';
	
	/**
	 * Parent category of this category
	 * 
	 * @var 	object wiki\data\category\Category
	 */
	public $parentCategory = null;
	
	/**
	 * Children of this category
	 *
	 * @var 	array<wiki\data\category\Category>
	 */
	public $children = null;
	
	/**
	 * Returns the parent category of this category.
	 *
	 * @return	object wiki\data\category\Category
	 */
	public function getParentCategory() {
		if($this->parentCategory === null) {
			if($this->parentID !== null) {
				$this->parentCategory = CategoryCache::getInstance()->getCategory($this->parentID);
			} else {
				$this->parentCategory = $this;
			}
		}
		return $this->parentCategory;
	}
	
	/**
	 * Returns the children of this category.
	 *
	 * @return 	array<wiki\data\category\Category>
	 */
	public function getChildren() {
		if($this->children === null) {
			$categoryStructure = CategoryCache::getInstance()->getChildren($this->categoryID);
			foreach($categoryStructure AS $categoryID) {
				$this->children[] = CategoryCache::getInstance()->getCategory($categoryID);
			}
		}
		return $this->children;
		
	}
	
	/**
	 * Returns all sub categories of this category.
	 *
	 * @return 	array<wiki\data\category\Category>
	 */
	public function getSubCategories($depth, $i=1, array $array = null) {
		if ($array === null) $array = array();
		if(is_array($this->getChildren()) && $i <= $depth) {
			foreach ($this->getChildren() AS $children) {
				$array[] = $children;
				if (is_array($children->getChildren())) {
					$array = $children->getSubCategories($depth, $i+1, $array);
				}
			}
		}
		return $array;
	}
	
	public function getDepth() {
		if($this->getParentCategory() == $this) return 1;
		
		return $this->getParentCategory()->getDepth() + 1;
	}
		
	/**
	 * Returns the top category of this category.
	 *
	 * @return	object wiki\data\category\Category
	 */
	public function getTopCategory() {
		if ($this->getParentCategory() == $this) return $this;
	
		return $this->getParentCategory()->getTopCategory();
	}
	
	public function getArticles() {
		return CategoryCache::getInstance()->getArticles($this->categoryID);
	}
	
	/**
	 * @see wcf\system\breadcrumb\IBreadcrumbProvider::getBreadcrumb()
	 */
	public function getBreadcrumb() {
		if($this->getParentCategory() != $this) {
			$this->getParentCategory()->getBreadcrumb();
		}
		WCF::getBreadcrumbs()->add(new Breadcrumb($this->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
				'application' => 'wiki',
				'object' => $this
		))));
	}
	
	/**
	 * @see	wcf\system\request\IRouteController::getID()
	 */
	public function getID() {
		return $this->categoryID;
	}
	
	/**
	 * @see	wcf\system\request\IRouteController::getTitle()
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->categoryName);
	}
}
