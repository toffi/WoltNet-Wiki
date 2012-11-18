<?php
namespace wiki\system\cache\builder;
use wiki\data\category\Article;

use wcf\system\acl\ACLHandler;
use wcf\system\cache\builder\ICacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\WCF;

/**
 * Caches the category permissions for a combination of user groups.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.cache.builder
 * @category 	WoltNet Wiki
 */
class ArticlePermissionCacheBuilder implements ICacheBuilder {

	/**
	 * @see wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array();
		list($cache, $groupIDsStr) = explode('-', $cacheResource['cache']);
		$groupIDs = explode(',', $groupIDsStr);

		if (count($groupIDs)) {
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('acl_option.packageID IN (?)', array(PackageDependencyHandler::getInstance()->getDependencies()));
			$conditionBuilder->add('acl_option.objectTypeID = ?', array(ACLHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')));
			$conditionBuilder->add('option_to_group.optionID = acl_option.optionID');
			$conditionBuilder->add('option_to_group.groupID IN (?)', array($groupIDs));
			$sql = "SELECT		option_to_group.groupID, option_to_group.objectID AS articleID, option_to_group.optionValue,
						acl_option.optionName AS permission
				FROM		wcf".WCF_N."_acl_option acl_option,
						wcf".WCF_N."_acl_option_to_group option_to_group
						".$conditionBuilder;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditionBuilder->getParameters());
			while ($row = $statement->fetchArray()) {
				if (!isset($data[$row['articleID']][$row['permission']])) $data[$row['articleID']][$row['permission']] = $row['optionValue'];
				else $data[$row['articleID']][$row['permission']] = $row['optionValue'] || $data[$row['articleID']][$row['permission']];
			}
		}

		return $data;
	}
}
