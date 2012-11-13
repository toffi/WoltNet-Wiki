<?php
namespace wiki\data\category;

use wcf\data\category\CategoryNode;
use wcf\data\category\ViewableCategoryNode;
use wcf\data\DatabaseObject;

class WikiCategoryNode extends ViewableCategoryNode {	
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
}
