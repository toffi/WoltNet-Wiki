<?php
namespace wiki\page;

use wiki\data\category\WikiCategoryNodeTree;
use wiki\data\article\ArticleCache;
use wcf\page\AbstractPage;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\category\CategoryHandler;
use wcf\system\WCF;
use wcf\data\user\UserList;

/**
 * Displays index page
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage page
 * @category WoltNet - Wiki
 */
class CategoryListPage extends AbstractPage {

    /**
     *
     * @see wcf\page\AbstractPage::$activeMenuItem
     */
    public $activeMenuItem = 'wiki.pageMenu.categoryList';

    /**
     *
     * @see wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;

    /**
     * category list
     *
     * @var array<wiki\data\category\Category>
     */
    public $categoryList = null;

    /**
     * objectTypeName for Wiki Categories
     *
     * @var string
     */
    public $objectTypeName = 'com.woltnet.wiki.category';

    public $statistics = array ();

    /**
     *
     * @see wcf\page\IPage::readData()
     */
    public function readData() {
        parent::readData();

        $categoryTree = new WikiCategoryNodeTree($this->objectTypeName);
        $this->categoryList = $categoryTree->getIterator();
        $this->categoryList->setMaxDepth(0);

        $articles = ArticleCache::getInstance()->getArticles();
        $this->statistics['articles'] = count($articles);
        $this->statistics['categories'] = count(CategoryHandler::getInstance()->getCategories($this->objectTypeName));

        $userList = new UserList();
        $userList->sqlOrderBy = 'user_table.userID DESC';
        $userList->sqlLimit = 1;
        $userList->readObjects();
        $this->statistics['newestMember'] = current($userList->getObjects());
        $this->statistics['totalMember'] = $userList->countObjects();
    }

    /**
     *
     * @see wcf\page\IPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();

        // load boxes
        DashboardHandler::getInstance()->loadBoxes('com.woltnet.wiki.CategoryListPage', $this);

        WCF::getTPL()->assign(array (
                'categoryList' => $this->categoryList,
                'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltnet.wiki.categoryList'),
                'sidebarName' => 'com.woltnet.wiki.categoryList',
                'wikiAnnouncement' => '',
                'statistics' => $this->statistics
        ));
    }
}
