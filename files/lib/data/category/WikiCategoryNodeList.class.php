<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategoryNodeList;

class WikiCategoryNodeList extends ViewableCategoryNodeList {
	/**
	 * @see	wcf\data\category\CategoryNodeList::$nodeClassName
	 */
	protected $nodeClassName = 'wiki\data\category\WikiCategoryNode';
}
