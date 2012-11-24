<?php
namespace wiki\form;
use wiki\data\category\suggestion\CategorySuggestionAction;
use wiki\data\category\WikiCategoryNodeList;
use wiki\data\category\suggestion\CategorySuggestion;

use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\system\package\PackageDependencyHandler;
use wcf\util\HeaderUtil;
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
	
	/**
	 * @see wcf\page\IPage::$supportI18n
	 */
	public $supportI18n = true;
	
	public $username = null;
	
	public $userID = 0;
	
	public $title = null;
	
	public $parentCategoryID = 0;
	
	public $reason = null;
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
			
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
		
		parent::readData();
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
		
		I18nHandler::getInstance()->readValues();
		
		if(isset($_POST['parentCategory'])) $this->parentCategoryID = intval($_POST['parentCategory']);
		if(isset($_POST['title'])) $this->title = escapeString($_POST['title']);
		if(isset($_POST['reason'])) $this->reason = escapeString($_POST['reason']);
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
	
	/**
	 * @see wcf\form\IForm::validate()
	 */
	public function validate() {
		parent::validate();
		
		if($this->parentCategoryID < 0) {
			throw new UserInputException('parentCategoryID', 'notValid');
		}
		
		if (!I18nHandler::getInstance()->validateValue('title')) {
			throw new UserInputException('title', 'notValid');
		}
		
		if (!I18nHandler::getInstance()->validateValue('reason')) {
			throw new UserInputException('reason', 'notValid');
		}
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
		
		$data = array(
			'title'			=> $this->title,
			'description'		=> $this->reason,
			'parentCategoryID'	=> $this->parentCategoryID,
			'userID'		=> $this->userID,
			'username'		=> $this->username,
			'time'			=> TIME_NOW
		);
		
		$this->objectAction = new CategorySuggestionAction(array(), 'create', $data);
		$returnValues = $this->objectAction->executeAction();
		
		// save i18n values
		$this->saveI18nValue($returnValues['returnValues'], 'title');
		$this->saveI18nValue($returnValues['returnValues'], 'reason');
		
		// disable assignment of i18n values
		I18nHandler::getInstance()->disableAssignValueVariables();
		
		$this->saved();
	}
	
	/**
	 * Saves i18n values.
	 *
	 * @param	wiki\data\category\suggestion\CategorySuggestion	$category
	 * @param	string							$columnName
	 */
	public function saveI18nValue(CategorySuggestion $category, $columnName) {
		if (!I18nHandler::getInstance()->isPlainValue($columnName)) {
			I18nHandler::getInstance()->save($columnName, 'wiki.category.'.$columnName, 'wiki.category', PackageDependencyHandler::getInstance()->getPackageID('com.woltnet.wiki'));
		}
	}
}