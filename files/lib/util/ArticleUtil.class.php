<?php
namespace wiki\util;

use wcf\util\StringUtil;
use wcf\system\WCF;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	util
 * @category 	WoltNet - Wiki
 */
class ArticleUtil {

	/**
	 * Returns the next translationID
	 *
	 * @return 	int 	$translationID
	 */
	public static function getNextTranslationID() {
		$translationID = 1;

		$sql = "SELECT translationID FROM wiki".WCF_N."_article` GROUP BY translationID ORDER BY translationID DESC LIMIT 1";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while($row = $statement->fetchArray()) {
			$translationID = $row['translationID'] + 1;
		}

		return $translationID;
	}
}
