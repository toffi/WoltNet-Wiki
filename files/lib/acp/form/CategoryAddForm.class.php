<?php
namespace wiki\acp\form;

use wcf\acp\form\AbstractCategoryAddForm;

/**
 * Implementation of a form to create categories.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	acp.form
 * @category	WoltNet Wiki
 */
class CategoryAddForm extends AbstractCategoryAddForm {
	/**
	 * @see wcf\acp\form\ACPForm::$activeMenuItem
	 */
	public $activeMenuItem = 'wiki.acp.menu.link.wiki.category.add';
	
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 * @see wcf\acp\form\AbstractCategoryAddForm::$title
	 */
	public $title = 'wiki.acp.category.add';
}
