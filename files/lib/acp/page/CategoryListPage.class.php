<?php
namespace wiki\acp\page;

use wcf\acp\page\AbstractCategoryListPage;

/**
 * Shows the category list.
 *
 * @author Rene Gessinger
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage acp.page
 * @category WoltNet - Wiki
 */
class CategoryListPage extends AbstractCategoryListPage {

	/**
	 *
	 * @see wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wiki.acp.menu.link.wiki.category.list';

	/**
	 *
	 * @see wcf\acp\form\AbstractCategoryAddForm::$objectTypeName
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
}
