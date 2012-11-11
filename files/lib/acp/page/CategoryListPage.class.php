<?php
namespace wiki\acp\page;
use wiki\data\category\CategoryTree;

use wcf\page\AbstractPage;
use wcf\system\menu\acp\ACPMenu;
use wcf\system\WCF;

/**
 * Shows the category list.
 *
 * @author	Rene Gessinger
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	acp.page
 * @category 	WoltNet - Wiki
 */
class CategoryListPage extends AbstractPage {
	/**
	 * List of all Top Categories
	 * 
	 * @var	array<wiki\data\category\Caegory>
	 */
	public $categoryTree = null;
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
		
		$this->categoryTree = CategoryTree::getInstance();
		$this->categoryTree->readTree();
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		WCF::getTPL()->assign(array(
			'categoryTree' => $this->categoryTree->getTree()
		));
	}
	
	/**
	 * @see	wcf\page\IPage::show()
	 */
	public function show() {
		// enable menu item
		ACPMenu::getInstance()->setActiveMenuItem('wiki.acp.menu.link.wiki.category.list');
		
		parent::show();
	}
}
