<?php
namespace wpbt\system\label\object\type;

use wiki\data\category\WikiCategoryNodeTree;
use wcf\system\label\object\type\AbstractLabelObjectTypeHandler;
use wcf\system\label\object\type\LabelObjectType;
use wcf\system\label\object\type\LabelObjectTypeContainer;

/**
 * Object type handler for categories.
 *
 * @author Jean-Marc Licht
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.label.object
 * @category Bugtracker
 */
class CategoryLabelObjectTypeHandler extends AbstractLabelObjectTypeHandler {
	/**
	 * category node list
	 */
	public $categoryList = null;
	
	/**
	 * object type id
	 *
	 * @var integer
	 */
	public $objectTypeID = 0;
	
	/**
	 *
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// get product list
		$categoryTree = new WikiCategoryNodeTree($this->objectTypeName);
		$this->categoryList = $categoryTree->getIterator();
	}
	
	/**
	 *
	 * @see wcf\system\label\object\type\AbstractLabelObjectTypeHandler::setObjectTypeID()
	 */
	public function setObjectTypeID($objectTypeID) {
		parent::setObjectTypeID($objectTypeID);
		
		// build label object type container
		$this->container = new LabelObjectTypeContainer($this->objectTypeID);
		
		foreach($this->categoryList as $category) {
			$objectType = new LabelObjectType($category->getTitle(), $category->catgoryID, ($category->getDepth() - 1));
			$this->container->add($objectType);
		}
	}
	
	/**
	 *
	 * @see wcf\system\label\object\type\ILabelObjectTypeHandler::save()
	 */
	public function save() {
	}
}
