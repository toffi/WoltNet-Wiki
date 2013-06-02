<?php
namespace wiki\data\article;

use wcf\system\user\object\watch\UserObjectWatchHandler;
use wcf\system\WCF;

/**
 * Represents a list of watched articles.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.project
 * @category 	WoltNet Wiki
 */
class WatchedArticleList extends ViewableArticleList {

    /**
     * Creates a new WatchedIssueList object.
     */
    public function __construct() {
        parent::__construct();

        // add conditions
        if(!empty($this->sqlSelects)) $this->sqlSelects .= ',';
        $this->sqlSelects .= 'article_version.time AS time';
        $this->sqlConditionJoins .= "LEFT JOIN wiki".WCF_N."_article_version article_version ON article_version.versionID = article.activeVersionID";

        $objectTypeIDArticle = UserObjectWatchHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article');
        $objectTypeIDCategory = UserObjectWatchHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.category');
    }
}
