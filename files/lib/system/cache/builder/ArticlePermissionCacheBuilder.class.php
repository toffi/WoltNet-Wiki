<?php
namespace wiki\system\cache\builder;

use wcf\system\acl\ACLHandler;
use wcf\system\cache\builder\AbstractCacheBuilder;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Caches the category permissions for a combination of user groups.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.cache.builder
 * @category WoltNet Wiki
 */
class ArticlePermissionCacheBuilder extends AbstractCacheBuilder {

    /**
     *
     * @see wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
     */
    public function rebuild(array $parameters) {
        $data = array ();
        $groupIDs = explode(',', $parameters['groups']);

        if(count($groupIDs)) {
            $conditionBuilder = new PreparedStatementConditionBuilder();
            $conditionBuilder->add('acl_option.packageID IN (?)', array (
                    PACKAGE_ID
            ));
            $conditionBuilder->add('acl_option.objectTypeID = ?', array (
                    ACLHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')
            ));
            $conditionBuilder->add('option_to_group.optionID = acl_option.optionID');
            $conditionBuilder->add('option_to_group.groupID IN (?)', array (
                    $groupIDs
            ));
            $sql = "SELECT		option_to_group.groupID, option_to_group.objectID AS versionID, option_to_group.optionValue,
                        acl_option.optionName AS permission
                FROM		wcf" . WCF_N . "_acl_option acl_option,
                        wcf" . WCF_N . "_acl_option_to_group option_to_group
                        " . $conditionBuilder;
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute($conditionBuilder->getParameters());
            while($row = $statement->fetchArray()) {
                if(! isset($data[$row['versionID']][$row['permission']]))
                    $data[$row['versionID']][$row['permission']] = $row['optionValue'];
                else
                    $data[$row['versionID']][$row['permission']] = $row['optionValue'] || $data[$row['versionID']][$row['permission']];
            }
        }

        return $data;
    }
}
