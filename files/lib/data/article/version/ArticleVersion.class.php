<?php
namespace wiki\data\article\version;
use wiki\data\WIKIDatabaseObject;

/**
 * @author	Rene Gessinger
 * @copyright	2013 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.version
 * @category 	WoltNet - Wiki
 */
class ArticleVersion extends WIKIDatabaseObject {
	/**
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'article_version';

	/**
	 * @see wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'versionID';

	/**
	 * @see DatabaseObject::__construct()
	 */
	public function __construct($id, $row = null, $object = null) {
		//we need to overload the constructor for active row
		if ($id !== null) {
			$sql = "SELECT	*
          FROM	".static::getDatabaseTableName()."
          WHERE	(".static::getDatabaseTableIndexName()." = ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($id));
			$row = $statement->fetchArray();

			if ($row === false) $row = array();
		}

		parent::__construct(null, $row, $object);
	}
}