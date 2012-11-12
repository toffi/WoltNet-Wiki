<?php
namespace wiki\page;
use wiki\data\category\CategoryCache;

use wcf\page\AbstractPage;
use wcf\system\dashboard\DashboardHandler;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\WCF;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	page
 * @category 	WoltNet - Wiki
 */
class IndexPage extends AbstractPage {
	/**
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
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->categoryList = CategoryCache::getInstance()->getTopCategories();
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		// load boxes
		DashboardHandler::getInstance()->loadBoxes('com.woltnet.wiki.IndexPage', $this);
	
		WCF::getTPL()->assign(array(
				'categoryList' 		=> $this->categoryList,
				'sidebarCollapsed'	=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltnet.wiki.index'),
				'sidebarName' 		=> 'com.woltnet.wiki.index',
				'wikiAnnouncement'	=> ''
		));
	}
}
