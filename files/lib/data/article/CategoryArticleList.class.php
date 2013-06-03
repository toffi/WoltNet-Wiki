<?php
namespace wiki\data\article;

use wiki\data\category\WikiCategory;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Provides extended functions for displaying a list of articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article
 * @category WoltNet Wiki
 */
class CategoryArticleList extends ViewableArticleList {

    /**
     * category object
     *
     * @var wiki\data\category\Category
     */
    public $category = null;

    /**
     *
     * @var integer
     */
    public $categoryIDs = '';

    /**
     * language
     *
     * @var integer
     */
    public $languageID = 0;

    /**
     * Creates a new CategoryProjectList object.
     *
     * @param wiki\data\category\WikiCategory $category
     * @param string $categoryIDs
     * @param integer $languageID
     */
    public function __construct(WikiCategory $category, $categoryIDs = '', $labelID = 0, $languageID = 0) {
        $this->category = $category;
        $this->categoryIDs = $categoryIDs;
        $this->languageID = $languageID;

        parent::__construct();

        // add conditions
        $this->getConditionBuilder()->add('article.categoryID IN (?)', array (
                $this->categoryIDs
        ));

        // fetch time
        $this->sqlConditionJoins .= " LEFT JOIN wiki" . WCF_N . "_article_version article_version ON article_version.versionID = article.activeVersionID";

        // filter by label id
        if($labelID) {
            $this->getConditionBuilder()->add("article.articleID IN (
                SELECT	articleID
                FROM	wiki" . WCF_N . "_article_label_to_object
                WHERE	labelID = ?
            )", array (
                    $labelID
            ));
        }

        // article language
        if($this->languageID) {
            $this->getConditionBuilder()->add('article.languageID = ?', array (
                    $this->languageID
            ));
        } else if(count(LanguageFactory::getInstance()->getContentLanguages()) > 0 && count(WCF::getUser()->getLanguageIDs())) {
            $this->getConditionBuilder()->add('(article.languageID IN (?))', array (
                    WCF::getUser()->getLanguageIDs()
            ));
        }
    }
}
