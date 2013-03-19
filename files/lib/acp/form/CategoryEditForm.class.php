<?php
namespace wiki\acp\form;

use wcf\acp\form\AbstractCategoryEditForm;

class CategoryEditForm extends AbstractCategoryEditForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wiki.acp.menu.link.wiki.category';
	
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'wiki.acp.category.edit';
}
