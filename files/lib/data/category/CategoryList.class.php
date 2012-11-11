<?php
namespace wiki\data\category;

use wcf\data\DatabaseObjectList;

/**
 * Represents a list of categories.
 * 
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>	
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet Wiki
 */
class CategoryList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wiki\data\category\Category';
}
