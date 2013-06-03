<?php
namespace wiki\data;

use wcf\data\DatabaseObject;

/**
 *
 * @author Jean-Marc Licht
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data
 * @category WoltNet - Wiki
 */
abstract class WIKIDatabaseObject extends DatabaseObject {
	
	/**
	 *
	 * @see wcf\data\IStorableObject::getDatabaseTableName()
	 */
	public static function getDatabaseTableName() {
		return 'wiki' . WCF_N . '_' . static::$databaseTableName;
	}
}
