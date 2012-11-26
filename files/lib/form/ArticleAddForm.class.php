<?php
namespace wiki\form;
use wiki\data\category\WikiCategoryNodeList;
use wiki\data\category\WikiCategory;
use wiki\data\article\ArticleAction;
use wiki\system\article\ArticlePermissionHandler;

use wcf\data\smiley\SmileyCache;
use wcf\system\WCF;
use wcf\system\request\LinkHandler;
use wcf\form\MessageForm;
use wcf\util\HeaderUtil;
use wcf\util\StringUtil;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\message\quote\MessageQuoteManager;
use wcf\util\UserUtil;
use wcf\data\moderation\queue\ModerationQueueActivationAction;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use wcf\system\exception\UserInputException;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\acl\ACLHandler;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\language\LanguageFactory;
use wcf\system\exception\NamedUserException;
use wcf\system\exception\IllegalLinkException;

/**
 * Shows the new article form.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	form
 * @category	WoltNet Wiki
 */
class ArticleAddForm extends MessageForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $action = 'add';
	public $templatename = 'articleAdd';
	public $username = null;
	
	/**
	 * id of the category
	 * @var integer
	 */
	public $categoryID = 0;
	
	/**
	 * category object for the given categoryID
	 * @var wiki\data\catagory\Category
	 */
	public $category = null;
	
	/**
	 * id of the category acl object type
	 * @var	integer
	 */
	public $aclObjectTypeID = 0;
	
	/**
	 * category node list
	 * @var	wiki\data\category\WikiCategoryNodeList
	 */
	public $categoryNodeList = null;
	
	/**
	 * @see	wcf\form\MessageForm::$enableMultilingualism
	 */
	public $enableMultilingualism = true;
	
	protected $article = null;
	
	/**
	 * objectTypeName for Wiki Categoires
	 *
	 * @var string
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
		
		if(isset($_GET['id'])) {
			$this->categoryID = intval($_GET['id']);
			$category = CategoryHandler::getInstance()->getCategory($this->categoryID);
				
			if($category !== null) $this->category = new WikiCategory($category);
				
			if ($this->category === null || !$this->category->categoryID) {
				throw new IllegalLinkException();
			}
		
			// check permissions
			$this->category->checkPermission(array('canViewCategory', 'canEnterCategory', 'canAddArticle'));
		}
		
		// quotes
		MessageQuoteManager::getInstance()->readParameters();
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
	
		WCF::getSession()->checkPermissions(array('user.wiki.article.write.canAddArticle'));
	
		// get acl object type id
		$aclObjectTypeName = 'com.woltnet.wiki.article';
		if ($aclObjectTypeName) {
			$this->aclObjectTypeID = ACLHandler::getInstance()->getObjectTypeID($aclObjectTypeName);
		}
	
		// default values
		if (!count($_POST)) {
			$this->username = WCF::getSession()->getVar('username');
	
			// multilingualism
			if (!empty($this->availableContentLanguages)) {
				if (!$this->languageID) {
					$language = LanguageFactory::getInstance()->getUserLanguage();
					$this->languageID = $language->languageID;
				}
	
				if (!isset($this->availableContentLanguages[$this->languageID])) {
					$languageIDs = array_keys($this->availableContentLanguages);
					$this->languageID = array_shift($languageIDs);
				}
			}
			
			// get all message ids from current conversation
			$sql = "SELECT	articleID
			FROM	wiki".WIKI_N."_article";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute();
			$articleIDs = array();
			while ($row = $statement->fetchArray()) {
				$articleIDs[] = $row['articleID'];
			}
				
			/*$renderedQuotes = MessageQuoteManager::getInstance()->getQuotesByObjectIDs('com.woltnet.wiki.article', $articleIDs);
			if (!empty($renderedQuotes)) {
				$this->text = implode("\n", $renderedQuotes);
			}*/
			MessageQuoteManager::getInstance()->initObjects('com.woltnet.wiki.article', $articleIDs);
		}
	
		// read categories
		$this->categoryNodeList = new WikiCategoryNodeList($this->objectTypeName);
	
		if($this->categoryNodeList->count() == 0) {
			throw new NamedUserException(WCF::getLanguage()->get('wiki.articleAdd.noCategories'));
		}
	
		if($this->categoryID != 0) {
			$category = CategoryHandler::getInstance()->getCategory($this->categoryID);
			WCF::getBreadcrumbs()->add(new Breadcrumb($category->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
					'object' 	=> $category
			))));
		}
	}
	
	/**
	 * @see wcf\form\IForm::readFormParameters()
	 */
	public function readFormParameters() {
		parent::readFormParameters();
	
		if(isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
		if(isset($_POST['category'])) $this->categoryID = intval($_POST['category']);
		
		// quotes
		MessageQuoteManager::getInstance()->readFormParameters();
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		if (WCF::getSession()->getPermission('mod.wiki.article.canManagePermissions') && $this->aclObjectTypeID) {
			ACLHandler::getInstance()->assignVariables($this->aclObjectTypeID);
		}
		
		MessageQuoteManager::getInstance()->assignVariables();
	
		WCF::getTPL()->assign(array(
				'categoryNodeList' 	=> $this->categoryNodeList,
				'categoryID'		=> $this->categoryID,
				'username'			=> $this->username,
				'aclObjectTypeID'	=> $this->aclObjectTypeID
		));
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function validate() {
		parent::validate();
	
		// username
		$this->validateUsername();
	
		// category
		$this->validateCategory();
	}
	
	/**
	 * Validates the username.
	 */
	protected function validateUsername() {
		// only for guests
		if (WCF::getUser()->userID == 0) {
			if (empty($this->username)) {
				throw new UserInputException('username');
			}
			if (!UserUtil::isValidUsername($this->username)) {
				throw new UserInputException('username', 'notValid');
			}
			if (!UserUtil::isAvailableUsername($this->username)) {
				throw new UserInputException('username', 'notAvailable');
			}
	
			WCF::getSession()->register('username', $this->username);
		}
	}
	
	/**
	 * Validates the selected CategoryID
	 */
	protected function validateCategory() {
		$category = CategoryHandler::getInstance()->getCategory($this->categoryID);
		
		if($category !== null) $category = new WikiCategory($category);
		
		$category->checkPermission(array('canAddArticle'));
	}
	
	/**
	 * @see wcf\form\IForm::save()
	 */
	public function save() {
		parent::save();
	
		// save article
		$data = array(
				'subject' => $this->subject,
				'categoryID' => $this->categoryID,
				'message' => $this->text,
				'userID' => (WCF::getUser()->userID ?: null),
				'username' => (WCF::getUser()->userID ? WCF::getUser()->username : $this->username),
				'time' => TIME_NOW,
				'languageID' => $this->languageID
		);
		$this->objectAction = new ArticleAction(array(), 'create', $data);
		$resultValues = $this->objectAction->executeAction();
	
		$this->article = $resultValues['returnValues'];
	
		// save acl
		if (WCF::getSession()->getPermission('mod.wiki.article.canManagePermissions') && $this->aclObjectTypeID) {
			ACLHandler::getInstance()->save($resultValues['returnValues']->articleID, $this->aclObjectTypeID);
			ACLHandler::getInstance()->disableAssignVariables();
			ArticlePermissionHandler::getInstance()->resetCache();
	
			//update user storage
			$userPermissions = array();
	
			$conditionBuilder = new PreparedStatementConditionBuilder();
			$conditionBuilder->add('acl_option.packageID IN (?)', array(PackageDependencyHandler::getInstance()->getDependencies()));
			$conditionBuilder->add('acl_option.objectTypeID = ?', array(ACLHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')));
			$conditionBuilder->add('option_to_user.optionID = acl_option.optionID');
			$conditionBuilder->add('option_to_user.userID = ?', array(WCF::getUser()->userID));
			$sql = "SELECT		option_to_user.objectID AS articleID, option_to_user.optionValue,
			acl_option.optionName AS permission
			FROM		wcf".WCF_N."_acl_option acl_option,
			wcf".WCF_N."_acl_option_to_user option_to_user
			".$conditionBuilder;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditionBuilder->getParameters());
			while ($row = $statement->fetchArray()) {
				$userPermissions[$row['articleID']][$row['permission']] = $row['optionValue'];
			}
	
			// update storage data
			UserStorageHandler::getInstance()->update(WCF::getUser()->userID, 'articleUserPermissions', serialize($userPermissions));
		}
	
		ModerationQueueActivationManager::getInstance()->addModeratedContent('com.woltnet.wiki.article', $this->article->articleID);
	
		$this->saved();
		
		MessageQuoteManager::getInstance()->saved();
	
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array(
				'application' => 'wiki',
				'object' => $this->article
		)));
		exit;
	}
}
