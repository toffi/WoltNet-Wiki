<?php
namespace wiki\page;
use wiki\data\category\WikiCategoryNodeTree;
use wiki\data\category\WikiCategory;
use wiki\data\article\CategoryArticleList;
use wiki\data\article\label\ArticleLabel;

use wcf\page\SortablePage;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\request\LinkHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;
use wcf\system\visitTracker\VisitTracker;

/**
 * Displays category page
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	page
 * @category 	WoltNet - Wiki
 */
class CategoryPage extends SortablePage {
    /**
     * given categoryID
     *
     * @var integer
     */
    public $categoryID = 0;

    public $title = null;

    /**
     * WikiCategory-Object of the given category
     *
     * @var wiki\datata\category\WikiCategory
     */
    public $category = null;

    /**
     * category node list
     *
     * @var wiki\data\category\WikiCategoryNodeList
     */
    public $categoryNodeList = null;

    /**
     * @see wcf\page\MultipleLinkPage::$itemsPerPage
     */
    public $itemsPerPage = WIKI_CATEGORY_ARTICLES_PER_PAGE;

    /**
     * @see wcf\page\SortablePage::$defaultSortField
     */
    public $defaultSortField = WIKI_CATEGORY_DEFAULT_SORT_FIELD;

    /**
     * @see wcf\page\SortablePage::$defaultSortField
     */
    public $defaultSortOrder = WIKI_CATEGORY_DEFAULT_SORT_ORDER;

    /**
     * @see wcf\page\SortablePage::$validSortFields
     */
    public $validSortFields = array('subject', 'username', 'time');

    /**
     * label id
     *
     * @var	integer
     */
    public $labelID = 0;

    /**
     * label list object
     *
     * @var	wiki\data\article\label\ArticleLabelList
     */
    public $labelList = null;

    /**
     * list filter
     *
     * @var	string
     */
    public $filter = '';

    /**
     * @see wcf\page\AbstractPage::$enableTracking
     */
    public $enableTracking = true;

    /**
     * @see wcf\page\MultipleLinkPage::$objectListClassName
     */
    public $objectListClassName = 'wiki\data\article\CategoryArticleList';

    /**
     * objectTypeName for Wiki Categories
     *
     * @var string
     */
    public $objectTypeName = 'com.woltnet.wiki.category';

    /**
     * @see wcf\page\IPage::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        if (isset($_REQUEST['id'])) $this->categoryID = intval($_REQUEST['id']);
        if(isset($_GET['title'])) $this->title = escapeString($_GET['title']);

        $category = CategoryHandler::getInstance()->getCategory($this->categoryID);

        if($category !== null) $this->category = new WikiCategory($category);

        if($this->category === null || !$this->category->categoryID || $this->title === null || $this->title != $this->category->getTitle()) {
            throw new IllegalLinkException();
        }

        $this->labelList = ArticleLabel::getLabelsByCategory($this->categoryID);
        if (isset($_REQUEST['labelID'])) {
            $this->labelID = intval($_REQUEST['labelID']);

            $validLabel = false;
            foreach ($this->labelList as $label) {
                if ($label->labelID == $this->labelID) {
                    $validLabel = true;
                    break;
                }
            }

            if (!$validLabel) {
                throw new IllegalLinkException();
            }
        }

        // check permissions
        if (!$this->category->isAccessible()) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @see wcf\page\AbstractPage::readData()
     */
    public function readData() {
        parent::readData();

        // get node tree
        $categoryTree = new WikiCategoryNodeTree($this->objectTypeName, $this->category->categoryID);
        $this->categoryNodeList = $categoryTree->getIterator();
        $this->categoryNodeList->setMaxDepth(0);

        foreach($this->category->getParentCategories() AS $categoryItem) {
            WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
                    'application' => 'wiki',
                    'object' => $categoryItem
            ))));
        }
        VisitTracker::getInstance()->trackObjectVisit('com.woltnet.wiki.category', $this->category->categoryID);
    }

    /**
     * @see wcf\page\AbstractPage::assignVariables()
     */
    public function assignVariables() {
        parent::assignVariables();

        // load boxes
        DashboardHandler::getInstance()->loadBoxes('com.woltnet.wiki.CategoryPage', $this);

        WCF::getTPL()->assign(array(
            'categoryList' => $this->categoryNodeList,
            'category' => $this->category,
            'filter' => $this->filter,
            'hasMarkedItems' => ClipboardHandler::getInstance()->hasMarkedItems(ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')),
            'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltnet.wiki.category'),
            'sidebarName' => 'com.woltnet.wiki.category',
            'labelID' => $this->labelID,
            'labelList' => $this->labelList
        ));
    }

    /**
     * @see wcf\page\MultipleLinkPage::initObjectList()
     */
    protected function initObjectList() {
        $this->objectList = new CategoryArticleList($this->category, $this->categoryID, $this->labelID);
        $this->objectList->setLabelList($this->labelList);
    }

    /**
     * Reads object list.
     */
    protected function readObjects() {
        $this->objectList->sqlLimit = $this->sqlLimit;
        $this->objectList->sqlOffset = $this->sqlOffset;
        if ($this->sqlOrderBy) $this->objectList->sqlOrderBy = $this->sqlOrderBy;
        $this->objectList->readObjects();
    }

    /**
     * @see wcf\page\ITrackablePage::getObjectID()
     */
    public function getObjectID() {
        return $this->categoryID;
    }

    /**
     * @see wcf\page\ITrackablePage::getObjectType()
     */
    public function getObjectType() {
        return 'com.woltlab.wiki.category';
    }
}
