<?php
namespace wiki\acp\page;

use wcf\acp\page\AbstractCategoryListPage;

/**
 * Shows the category list.
 *
 * @author Rene Gessinger
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage acp.page
 * @category WoltNet - Wiki
 */
class CategoryListPage extends AbstractCategoryListPage {
	public $activeMenuItem = 'wiki.acp.menu.link.wiki.category.list';
	public $objectTypeName = 'com.woltnet.wiki.category';
}
