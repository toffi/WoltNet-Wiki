<?php
namespace wiki\data\category\suggestion;

use wcf\data\DatabaseObjectEditor;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category.suggestion
 * @category 	WoltNet - Wiki
 */
class CategorySuggestionEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wiki\data\category\suggestion\CategorySuggestion';
}
