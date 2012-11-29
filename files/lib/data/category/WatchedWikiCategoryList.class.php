<?php
namespace wiki\data\category;

use wcf\data\category\CategoryList;

/**
 * Represents a list of watched categories.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.project
 * @category 	WoltNet Wiki
 */
class WatchedWikiCategoryList extends CategoryList {

	/**
	 * @see wiki\data\project\ViewableArticleList::$decoratorClassName
	 */
	public $decoratorClassName = 'wiki\data\article\WatchedWikiCategory';
}
