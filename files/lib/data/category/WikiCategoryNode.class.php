<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategoryNode;
use wcf\data\DatabaseObject;
use wcf\system\language\LanguageFactory;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\WCF;

/**
 * Represents a wiki category node.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategoryNode extends ViewableCategoryNode {
    /**
     * child category nodelist
     *
     * @var wiki\data\category\WikiCategorynodeList
     */
    protected $subCategories = null;

    /**
     * Count of articles in this category
     *
     * @var integer
     */
    public $articles = null;

    /**
     * Count of unread articles in this category
     *
     * @var integer
     */
    public $unreadArticles = null;

    /**
     * objectTypeName for Wiki Categoires
     *
     * @var string
     */
    public $objectTypeName = 'com.woltnet.wiki.category';

    /**
     * @see    wcf\data\category\CategoryNode::fulfillsConditions()
     */
    protected function fulfillsConditions(DatabaseObject $category) {
        if (parent::fulfillsConditions($category)) {
            $category = new WikiCategory($category);

            return $category->isAccessible();
        }

        return false;
    }

    /**
     * Returns all children of this category
     *
     * @return wiki\data\category\WikiCategorynodeList
     */
    public function getChildCategories($depth = 0) {
        if($this->subCategories === null) {
            $this->subCategories = new WikiCategorynodeList($this->objectTypeName, $this->categoryID);
            if($depth > 0) $this->subCategories->setMaxDepth($depth);
        }

        return $this->subCategories;
    }

    /**
     * Returns count of articles
     */
    public function getArticles() {
        if($this->articles === null) {
            $conditions = new PreparedStatementConditionBuilder();
            $conditions->add('article.categoryID = ?', array($this->categoryID));
            //$conditions->add('article.isActive = ?', array(1));
            //$conditions->add('article.isDeleted = ?', array(0));
            if (count(LanguageFactory::getInstance()->getContentLanguages()) > 0 && count(WCF::getUser()->getLanguageIDs())) {
                $conditions->add('(article.languageID IN (?) OR article.languageID IS NULL)', array(WCF::getUser()->getLanguageIDs()));
            }
            $sql = "SELECT COUNT(articleID) AS count
                    FROM wiki".WCF_N."_article AS article
                    ".$conditions;
            $statement = WCF::getDB()->prepareStatement($sql);
            $statement->execute($conditions->getParameters());
            $row = $statement->fetchArray();
            $this->articles = $row['count'];
        }
        return $this->articles;
    }

    /**
     * Returns count of unread articles
     */
    public function getUnreadArticles() {
        return 0;
    }
}
