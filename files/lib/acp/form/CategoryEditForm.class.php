<?php
namespace wiki\acp\form;

use wcf\acp\form\AbstractCategoryEditForm;

/**
 * Shows the category edit form.
 *
 * @author Rene Gessinger
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage acp.form
 * @category WoltNet - Wiki
 */
class CategoryEditForm extends AbstractCategoryEditForm {

	/**
	 *
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wiki.acp.menu.link.wiki.category';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'wiki.acp.category.edit';
}
