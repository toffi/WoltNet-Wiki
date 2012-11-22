<?php
namespace wiki\form;
use wiki\data\category\WikiCategoryNodeList;

use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\language\I18nHandler;
use wcf\form\AbstractForm;
use wcf\system\WCF;

/**
 * Shows the new categorySuggestion form.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	form
 * @category	WoltNet Wiki
 */
class CategorySuggestionAddForm extends AbstractForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templatename = 'categorySuggestionAdd';
	
	/**
	 * @see wcf\page\AbstractPage::$loginRequired
	 */
	public $loginRequired = false;
	
	/**
	 * category node list
	 * @var	wiki\data\category\WikiCategoryNodeList
	 */
	public $categoryNodeList = null;
	
	/**
	 * objectTypeName for Wiki Categoires
	 *
	 * @var string
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	public $username = null;
	
	public $userID = 0;
	
	public $title = null;
	
	public $parentCategoryID = 0;
	
	public $reason = null;
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
	
		//WCF::getSession()->checkPermissions(array('user.wiki.category.write.canSuggestCategories'));
		
		// read categories
		$this->categoryNodeList = new WikiCategoryNodeList($this->objectTypeName);
		
		if($this->categoryNodeList->count() == 0) {
			throw new NamedUserException(WCF::getLanguage()->get('wiki.articleAdd.noCategories'));
		}
		
		// add userdata
		$this->userID = WCF::getUser()->userID;
		$this->username = WCF::getUser()->username;
		
		I18nHandler::getInstance()->register('title');
		I18nHandler::getInstance()->register('reason');
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
		
		I18nHandler::getInstance()->assignVariables();
	
		WCF::getTPL()->assign(array(
			'categoryNodeList' 	=> $this->categoryNodeList,
			'partentCategoryID'	=> $this->parentCategoryID,
			'title'			=> $this->title,
			'reason'		=> $this->reason
		));
	}
}