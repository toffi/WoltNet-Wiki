<?php
namespace wiki\page;

use wcf\page\SortablePage;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\WCF;

/**
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage page
 * @category WoltNet - Wiki
 */
class WatchedArticleListPage extends SortablePage {
	/**
	 *
	 * @see wcf\page\AbstractPage::$activeMenuItem
	 */
	public $activeMenuItem = 'wiki.pageMenu.categoryList';
	
	/**
	 *
	 * @see wcf\page\SortablePage::$defaultSortField
	 */
	public $defaultSortField = WIKI_CATEGORY_DEFAULT_SORT_FIELD;
	
	/**
	 *
	 * @see wcf\page\SortablePage::$defaultSortOrder
	 */
	public $defaultSortOrder = WIKI_CATEGORY_DEFAULT_SORT_ORDER;
	
	/**
	 *
	 * @see wcf\page\MultipleLinkPage::$itemsPerPage
	 */
	public $itemsPerPage = WIKI_CATEGORY_ARTICLES_PER_PAGE;
	
	/**
	 *
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = true;
	
	/**
	 *
	 * @see wcf\page\MultipleLinkPage::$objectListClassName
	 */
	public $objectListClassName = 'wiki\data\article\WatchedArticleList';
	
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
	 *
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array (
				'hasMarkedItems' => ClipboardHandler::getInstance()->hasMarkedItems(ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')) 
		));
	}
}
