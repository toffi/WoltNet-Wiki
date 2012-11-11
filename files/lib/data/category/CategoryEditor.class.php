<?php
namespace wiki\data\category;

use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;
use wcf\system\cache\CacheHandler;

/**
 * Manages the category cache.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet - Wiki
 */
class CategoryEditor extends DatabaseObjectEditor implements IEditableCachedObject {
	/**
	 * @see	wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wiki\data\category\Category';
	
	/**
	 * @see wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		static::resetDataCache();
	
		CacheHandler::getInstance()->clear(WIKI_DIR.'cache', 'cache.category.php');
	}
}
