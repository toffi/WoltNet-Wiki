<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategoryNodeList;

/**
 * Represents a list of wiki category nodes.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategoryNodeList extends ViewableCategoryNodeList {
	/**
	 * @see	wcf\data\category\CategoryNodeList::$nodeClassName
	 */
	protected $nodeClassName = 'wiki\data\category\WikiCategoryNode';
}
