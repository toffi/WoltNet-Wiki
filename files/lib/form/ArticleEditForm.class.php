<?php
namespace wiki\form;
use wiki\data\article\ArticleCache;

use wcf\form\MessageForm;
use wcf\system\WCF;
use wcf\system\acl\ACLHandler;
use wcf\system\message\quote\MessageQuoteManager;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\IllegalLinkException;

/**
 * Shows the edit article form.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	form
 * @category	WoltNet Wiki
 */
class ArticleEditForm extends MessageForm {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templatename = 'articleAdd';
	
	public $action = 'edit';
	
	public $username = null;
	
	/**
	 * id of the category acl object type
	 * @var	integer
	 */
	public $aclObjectTypeID = 0;
	
	/**
	 * @see	wcf\form\MessageForm::$enableMultilingualism
	 */
	public $enableMultilingualism = true;
	
	/**
	 * @see	wcf\form\MessageForm::$showSignatureSetting
	 */
	public $showSignatureSetting = 0;
	
	public $activateArticle = 0;
	
	protected $article = null;
	
	protected $articleID = 0;
	
	/**
	 * @see wcf\page\IPage::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();
	
		if(isset($_GET['id'])) {
			$this->articleID = intval($_GET['id']);
		}
		else {
			throw new IllegalLinkException();
		}
		$this->article = ArticleCache::getInstance()->getArticle($this->articleID);
		
		if($this->article === null) throw new IllegalLinkException();
		
		if(!$this->article->isEditable()) throw new PermissionDeniedException();
	
		// quotes
		MessageQuoteManager::getInstance()->readParameters();
	}
	
	/**
	 * @see wcf\page\IPage::readData()
	 */
	public function readData() {
		parent::readData();
	
		// get acl object type id
		$aclObjectTypeName = 'com.woltnet.wiki.article';
		if ($aclObjectTypeName) {
			$this->aclObjectTypeID = ACLHandler::getInstance()->getObjectTypeID($aclObjectTypeName);
		}
	
		// default values
		if (!count($_POST)) {
			$this->username = WCF::getSession()->getVar('username');
			
			$this->text = $this->article->getMessage();
			$this->subject = $this->article->getTitle();
	
			$sql = "SELECT	articleID
				FROM	wiki".WIKI_N."_article";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute();
			$articleIDs = array();
			while ($row = $statement->fetchArray()) {
				$articleIDs[] = $row['articleID'];
			}
				
			//manage quotes
			MessageQuoteManager::getInstance()->initObjects('com.woltnet.wiki.article', $articleIDs);
	
			if($this->article->getCategory()->getPermission('canActivateArticle')) $this->activateArticle = 1;
		}
	}
	
	/**
	 * @see wcf\page\IPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();
	
		if ($this->article->getCategory()->getPermission('canManagePermissions') && $this->aclObjectTypeID) {
			ACLHandler::getInstance()->assignVariables($this->aclObjectTypeID);
		}
	
		MessageQuoteManager::getInstance()->assignVariables();
	
		WCF::getTPL()->assign(array(
				'username'		=> $this->username,
				'articleID'		=> $this->articleID,
				'article'		=> $this->article,
				'aclObjectTypeID'	=> $this->aclObjectTypeID,
				'activateArticle'	=> $this->activateArticle
		));
	}
}
