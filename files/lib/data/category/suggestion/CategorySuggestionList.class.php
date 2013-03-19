<?php
namespace wiki\data\category\suggestion;

use wcf\data\DatabaseObjectList;

/**
 * @author	Rene Gessinger
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category.suggestion
 * @category 	WoltNet - Wiki
 */
class CategorySuggestionList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wiki\data\category\suggestion\CategorySuggestion';
}
