<?php
namespace wiki\data\category;

/**
 * Represents a category node
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet - Wiki
 */
class CategoryNode {
	/**
	 * Category of this Node
	 * 
	 * @var wiki\data\category\Category
	 */
	public $category = null;
	
	/**
	 * Creates a new CategoryNode object.
	 *
	 * @param	integer		$categoryID
	 */
	public function __construct($categoryID) {
		$this->category = CategoryCache::getInstance()->getCategory($categoryID);
	}
	
	/**
	 * Returns all children of this node
	 * 
	 * @return array<wiki\data\category\CategoryNode>
	 */
	public function getAllChildren() {
		if(is_array($this->category->getChildren())) {
			foreach($this->category->getChildren() AS $children) {
				$children = new CategoryNode($children->categoryID);
				$children->getAllChildren();
			}
		}
		return $this;
	}
	
	/**
	 * Returns category of this category node
	 * 
	 * @return wiki\data\category\Category
	 */
	public function getCategory() {
		return $this->category;
	}
}