<?php
namespace wiki\acp\form;

use wcf\acp\form\AbstractCategoryAddForm;

class CategoryAddForm extends AbstractCategoryAddForm {
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'wiki.acp.category.add';
}
