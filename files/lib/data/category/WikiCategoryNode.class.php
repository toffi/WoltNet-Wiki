<?php
namespace wiki\data\category;

use wiki\data\article\CategoryArticleList;
use wcf\data\category\CategoryNode;
use wcf\data\DatabaseObject;

/**
 * Represents a wiki category node.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.category
 * @category WoltNet Wiki
 */
class WikiCategoryNode extends CategoryNode {

	/**
	 * child category nodelist
	 *
	 * @var wiki\data\category\WikiCategorynodeList
	 */
	protected $subCategories = null;

	/**
	 * Count of articles in this category
	 *
	 * @var integer
	 */
	public $articles = null;

	/**
	 * Count of unread articles in this category
	 *
	 * @var integer
	 */
	public $unreadArticles = null;

	/**
	 * objectTypeName for Wiki Categoires
	 *
	 * @var string
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';

	/**
	 *
	 * @see wcf\data\category\CategoryNode::fulfillsConditions()
	 */
	protected function fulfillsConditions(DatabaseObject $category) {
		if(parent::fulfillsConditions($category)) {
			$category = new WikiCategory($category);
			
			return $category->isAccessible();
		}
		
		return false;
	}

	/**
	 * Returns all children of this category
	 *
	 * @return wiki\data\category\WikiCategoryNodeList
	 */
	public function getChildCategories($depth = 0) {
		if($this->subCategories === null) {
			$this->subCategories = new WikiCategoryNodeTree($this->objectTypeName, $this->categoryID);
			if($depth > 0)
				$this->subCategories->setMaxDepth($depth);
		}
		
		return $this->subCategories;
	}

	/**
	 * Returns count of articles
	 */
	public function getArticles() {
		if($this->articles === null) {
			$articleList = new CategoryArticleList(new WikiCategory($this->getDecoratedObject()), $this->categoryID);
			$articleList->readObjects();
			$this->articles = $articleList->getObjects();
		}
		return $this->articles;
	}

	/**
	 * Returns count of unread articles
	 */
	public function getUnreadArticles() {
		return array ();
	}
}
