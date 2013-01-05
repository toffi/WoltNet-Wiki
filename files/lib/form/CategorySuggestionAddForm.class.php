<?php
namespace wiki\form;
use wiki\data\category\suggestion\CategorySuggestionEditor;
use wiki\data\category\suggestion\CategorySuggestionAction;
use wiki\data\category\WikiCategoryNodeList;
use wiki\data\category\suggestion\CategorySuggestion;

use wcf\system\exception\UserInputException;
use wcf\system\request\LinkHandler;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
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
	public $loginRequired = true;
	
	/**
	 * @see wcf\page\AbstractPage::$neededPermissions
	 */
	public $neededPermissions = array('user.wiki.category.write.canSuggestCategories');
	
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
	
	public $title = '';
	
	public $parentCategoryID = 0;
	
	public $reason = '';
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		// read categories
		$this->categoryNodeList = new WikiCategoryNodeList($this->objectTypeName);
		
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
		if (I18nHandler::getInstance()->isPlainValue('title')) $this->title = I18nHandler::getInstance()->getValue('title');
		if (I18nHandler::getInstance()->isPlainValue('reason')) $this->reason = I18nHandler::getInstance()->getValue('reason');
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
			'reason'		=> $this->reason,
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
		
		// show success message
		WCF::getTPL()->assign('success', true);
		
		$this->saved();
	}
	
	/**
	 * Saves i18n values.
	 *
	 * @param	wiki\data\category\suggestion\CategorySuggestion	$category
	 * @param	string							$columnName
	 */
	public function saveI18nValue(CategorySuggestion $category, $columnName) {
		if (!I18nHandler::getInstance()->isPlainValue($columnName)) {;
			$categoryID = $category->suggestionID;
			I18nHandler::getInstance()->save($columnName, 'wiki.category.suggestion.suggestion'.$categoryID.StringUtil::firstCharToUpperCase($columnName), 'wiki.category', PACKAGE_ID);

			// update category name
			$categoryEditor = new CategorySuggestionEditor($category);
			$categoryEditor->update(array(
					$columnName => 'wiki.category.suggestion.suggestion'.$categoryID.StringUtil::firstCharToUpperCase($columnName)
			));
		}
	}
}