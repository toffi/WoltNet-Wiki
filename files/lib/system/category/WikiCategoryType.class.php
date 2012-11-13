<?php
namespace wiki\system\category;

use wcf\system\category\AbstractCategoryType;

class WikiCategoryType extends AbstractCategoryType {
	/**
	 * @see wcf\system\category\AbstractCategoryType::$permissionPrefix
	 */
	protected $permissionPrefix = 'admin.category';
	
	/**
	 * @see wcf\system\category\AbstractCategoryType::$langVarPrefix
	 */
	protected $langVarPrefix = 'wiki.category';
}
