<?php
namespace wiki\system\cache\builder;
use wiki\data\category\CategoryList;
use wcf\system\cache\builder\ICacheBuilder;

/**
 * Caches all categories, the structure of categories and all moderators.
 * 
 * @author	Jean-Marc Licht
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.cache.builder
 * @category 	WoltNet Wiki
 */
class CategoryCacheBuilder implements ICacheBuilder {
	/**
	 * @see wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array(
			'categories' => array(), 
			'categoryStructure' => array()
		);
		
		// categories
		$categoryList = new CategoryList();
		$categoryList->sqlOrderBy = 'category.parentID ASC, category.position ASC';
		$categoryList->sqlLimit = 0;
		$categoryList->readObjects();
		$data['categories'] = $categoryList->getObjects();
		
		// category structure
		foreach ($categoryList->getObjects() as $category) {
			$data['categoryStructure'][$category->parentID][] = $category->categoryID;
		}
		
		return $data;
	}
}
