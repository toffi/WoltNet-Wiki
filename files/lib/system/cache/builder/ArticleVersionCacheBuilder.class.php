<?php
namespace wiki\system\cache\builder;

use wiki\data\article\version\ArticleVersion;
use wcf\system\WCF;
use wcf\system\cache\builder\AbstractCacheBuilder;

/**
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.cache.builder
 * @category WoltNet - Wiki
 */
class ArticleVersionCacheBuilder extends AbstractCacheBuilder {

    /**
     *
     * @see wcf\system\cache\builder\AbstractCacheBuilder::rebuild()
     */
    public function rebuild(array $parameters) {
        $data = array (
                'versions' => array ()
        );

        $sql = "SELECT
        versionID
      FROM
        wiki" . WCF_N . "_article_version";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();
        while($row = $statement->fetchArray()) {
            $data['versions'][$row['versionID']] = new ArticleVersion($row['versionID']);
        }

        return $data;
    }
}
