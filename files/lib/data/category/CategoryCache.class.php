<?php
namespace wiki\data\category;

use wcf\system\cache\CacheHandler;
use wcf\system\SingletonFactory;
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
class CategoryCache extends SingletonFactory {
	/**
	 * cached category structure
	 * @var array
	 */
	protected $cachedCategoryStructure = array();
	
	/**
	 * cached categories
	 * @var array<wiki\data\category\Category>
	 */
	protected $cachedCategories = array();
	
	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// get category cache
		CacheHandler::getInstance()->addResource('category', WIKI_DIR.'cache/cache.category.php', 'wiki\system\cache\builder\CategoryCacheBuilder');
		$this->cachedCategoryStructure = CacheHandler::getInstance()->get('category', 'categoryStructure');
		$this->cachedCategories = CacheHandler::getInstance()->get('category', 'categories');
	}
	
	/**
	 * Gets the category with the given category id from cache.
	 *
	 * @param 	integer		$categoryID
	 * @return	wiki\data\category\Category
	 */
	public function getCategory($categoryID) {
		if (!isset($this->cachedCategories[$categoryID])) {
			return null;
		}
		
		return $this->cachedCategories[$categoryID];
	}
	
	/**
	 * Returns the children of a category.
	 *
	 * @param	integer		$parentID
	 * @return	array<integer>
	 */
	public function getChildren($parentID = null) {
		if (!isset($this->cachedCategoryStructure[$parentID])) return array();
	
		return $this->cachedCategoryStructure[$parentID];
	}
	
	/**
	 * Gets the number of articles.
	 *
	 * @param	integer		$categoryID
	 * @return	integer
	 */
	public function getArticles($categoryID) {
		if (isset($this->cachedCategories[$categoryID])) {
			return $this->cachedCategories[$categoryID]->articles;
		}
	
		return 0;
	}
	
	/**
	 * Returns a list of all top categories.
	 *
	 * @return	array<wiki\data\category\Category>
	 */
	public function getTopCategories() {
		$categories = array();
		
		if(array_key_exists(null, $this->cachedCategoryStructure)) {
			foreach($this->cachedCategoryStructure[null] AS $categoryID) {
				$categories[$categoryID] = $this->getCategory($categoryID);
			}
		}
		return $categories;
	}
	
	/**
	 * Returns a list of all categories.
	 *
	 * @return	array<wiki\data\category\Category>
	 */
	public function getCategories() {
		return $this->cachedCategories;
	}
}
