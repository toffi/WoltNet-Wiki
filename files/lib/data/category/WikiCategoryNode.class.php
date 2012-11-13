<?php
namespace wiki\data\category;

use wiki\system\category\WikiCategoryType;

use wcf\data\category\ViewableCategoryNode;
use wcf\system\category\CategoryHandler;
use wcf\data\DatabaseObject;

/**
 * Represents a wiki category node.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategoryNode extends ViewableCategoryNode {
	/**
	 * objectTypeName for Wiki Categoires
	 *
	 * @var string
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 * @see    wcf\data\category\CategoryNode::fulfillsConditions()
	 */
	protected function fulfillsConditions(DatabaseObject $category) {
		if (parent::fulfillsConditions($category)) {
			$category = new WikiCategory($category);
	
			return $category->isAccessible();
		}
	
		return false;
	}
	
	/**
	 * Returns all children of this category
	 * 
	 * @return wiki\data\category\WikiCategorynodeList
	 */
	public function getChildCategories($depth = 0) {
		$childList = new WikiCategorynodeList($this->objectTypeName, $this->categoryID);
		$childList->setMaxDepth($depth);
		
		return $childList;
	}
	
	//TODO: implement this
	/**
	 * Returns count of articles
	 */
	public function getArticles() {
		return $this->articles;;
	}
	
	//TODO: implement this
	/**
	 * Returns count of unread articles
	 */
	public function getUnreadArticles() {
		return 0;
	}
}
