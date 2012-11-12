<?php
namespace wiki\data\category;

use wcf\system\SingletonFactory;

/**
 * Represents a category tree
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet - Wiki
 */
class CategoryTree extends SingletonFactory {
	/**
	 * category tree
	 * 
	 * @var array<wiki\data\category\CategoryNode>
	 */
	public $categories = null;
	
	public static $maxDepth = 0;
	
	/**
	 * @see wcf\system.SingletonFactory::init()
	 */
	public function init() {
		$this->categories = CategoryCache::getInstance()->getTopCategories();
		foreach($this->categories AS $index=>$category) {
			$this->categories[$index] = new CategoryNode($category->categoryID);
		}
	}
	
	public function getMaxDepth() {
		return static::$maxDepth;
	}
	
	/**
	 * reads the full category tree
	 */
	public function readTree() {
		foreach($this->categories AS $index=>$category) {
			$this->categories[$index] = $category->getAllChildren();
		}
	}
	
	/**
	 * Returns full category tree
	 * 
	 * @return array<wiki\data\category\CategoryNode>
	 */
	public function getTree() {
		return $this->categories;
	}
}
