<?php
namespace wiki\page;

use wiki\data\category\WikiCategoryNodeTree;
use wiki\data\category\WikiCategory;
use wiki\data\article\CategoryArticleList;
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
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage page
 * @category WoltNet - Wiki
 */
class CategoryPage extends SortablePage {

	/**
	 * given categoryID
	 *
	 * @var integer
	 */
	public $categoryID = 0;

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
	 *
	 * @see wcf\page\MultipleLinkPage::$itemsPerPage
	 */
	public $itemsPerPage = WIKI_CATEGORY_ARTICLES_PER_PAGE;

	/**
	 *
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = WIKI_CATEGORY_DEFAULT_SORT_FIELD;

	/**
	 *
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortOrder = WIKI_CATEGORY_DEFAULT_SORT_ORDER;

	/**
	 *
	 * @see wcf\page\SortablePage::$validSortFields
	 */
	public $validSortFields = array (
			'subject',
			'username',
			'time' 
	);

	/**
	 * label filter
	 *
	 * @var array<integer>
	 */
	public $labelIDs = array ();

	/**
	 * list of available label groups
	 *
	 * @var array<wcf\data\label\group\ViewableLabelGroup>
	 */
	public $labelGroups = array ();

	/**
	 * list filter
	 *
	 * @var string
	 */
	public $filter = '';

	/**
	 *
	 * @see wcf\page\AbstractPage::$enableTracking
	 */
	public $enableTracking = true;

	/**
	 *
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
	 *
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if(isset($_REQUEST['id']))
			$this->categoryID = intval($_REQUEST['id']);
		
		$category = CategoryHandler::getInstance()->getCategory($this->categoryID);
		
		if($category !== null)
			$this->category = new WikiCategory($category);
		
		if($this->category === null || ! $this->category->categoryID) {
			throw new IllegalLinkException();
		}
		
		if(isset($_REQUEST['labelIDs']) && is_array($_REQUEST['labelIDs'])) {
			$this->labelIDs = $_REQUEST['labelIDs'];
			
			$validLabel = false;
			foreach($this->labelIDs as $labelID) {
				foreach($this->labelGroups as $labelGroup) {
					if($labelGroup->isValid($labelID)) {
						$validLabel = true;
						break;
					}
				}
				if(! $validLabel) {
					throw new IllegalLinkException();
				}
				$validLabel = false;
			}
		}
		
		// check permissions
		if(! $this->category->isAccessible()) {
			throw new PermissionDeniedException();
		}
	}

	/**
	 *
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		// get node tree
		$categoryTree = new WikiCategoryNodeTree($this->objectTypeName, $this->category->categoryID);
		$this->categoryNodeList = $categoryTree->getIterator();
		$this->categoryNodeList->setMaxDepth(0);
		
		$this->labelGroups = $this->category->getAvailableLabelGroups();
		
		foreach($this->category->getParentCategories() as $categoryItem) {
			WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array (
					'application' => 'wiki',
					'object' => $categoryItem 
			))));
		}
		VisitTracker::getInstance()->trackObjectVisit('com.woltnet.wiki.category', $this->category->categoryID);
	}

	/**
	 *
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		// load boxes
		DashboardHandler::getInstance()->loadBoxes('com.woltnet.wiki.CategoryPage', $this);
		
		WCF::getTPL()->assign(array (
				'categoryList' => $this->categoryNodeList,
				'category' => $this->category,
				'filter' => $this->filter,
				'hasMarkedItems' => ClipboardHandler::getInstance()->hasMarkedItems(ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')),
				'sidebarCollapsed' => UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltnet.wiki.category'),
				'sidebarName' => 'com.woltnet.wiki.category',
				'labelGroups' => $this->labelGroups,
				'labelIDs' => $this->labelIDs 
		));
	}

	/**
	 *
	 * @see wcf\page\MultipleLinkPage::initObjectList()
	 */
	protected function initObjectList() {
		$this->objectList = new CategoryArticleList($this->category, $this->categoryID, $this->labelIDs);
	}

	/**
	 * Reads object list.
	 */
	protected function readObjects() {
		$this->objectList->sqlLimit = $this->sqlLimit;
		$this->objectList->sqlOffset = $this->sqlOffset;
		if($this->sqlOrderBy)
			$this->objectList->sqlOrderBy = $this->sqlOrderBy;
		$this->objectList->readObjects();
	}

	/**
	 *
	 * @see wcf\page\ITrackablePage::getObjectID()
	 */
	public function getObjectID() {
		return $this->categoryID;
	}

	/**
	 *
	 * @see wcf\page\ITrackablePage::getObjectType()
	 */
	public function getObjectType() {
		return 'com.woltlab.wiki.category';
	}
}
