<?php
namespace wiki\form;
use wiki\data\article\ArticleCache;
use wiki\data\article\ArticleAction;
use wiki\system\article\ArticlePermissionHandler;

use wcf\form\MessageForm;
use wcf\system\WCF;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\system\acl\ACLHandler;
use wcf\system\message\quote\MessageQuoteManager;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\moderation\queue\ModerationQueueActivationManager;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\UserInputException;
use wcf\util\UserUtil;

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
     * @see wcf\form\IForm::readFormParameters()
     */
    public function readFormParameters() {
        parent::readFormParameters();

        if(isset($_POST['username'])) $this->username = StringUtil::trim($_POST['username']);
        if(isset($_POST['activateArticle']) && WCF::getSession()->getPermission('mod.wiki.article.canActivateArticle')) $this->activateArticle = intval($_POST['activateArticle']);

        // quotes
        MessageQuoteManager::getInstance()->readFormParameters();
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
                FROM	wiki".WCF_N."_article";
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

    /**
     * @see wcf\form\IForm::save()
     */
    public function validate() {
        parent::validate();

        // username
        $this->validateUsername();
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
     * @see wcf\form\IForm::save()
     */
    public function save() {
        parent::save();

        // save article
        $data = array(
                'subject' 	=> $this->subject,
                'categoryID' 	=> $this->article->categoryID,
                'message' 	=> $this->text,
                'userID' 	=> (WCF::getUser()->userID ?: null),
                'username' 	=> (WCF::getUser()->userID ? WCF::getUser()->username : $this->username),
                'time' 		=> TIME_NOW,
                'languageID' 	=> $this->article->languageID,
                'enableSmilies'	=> $this->enableSmilies,
                'enableHtml'	=> $this->enableHtml,
                'enableBBCodes'	=> $this->enableBBCodes,
                'parentID'	=> $this->article->articleID,
                'translationID'	=> $this->article->translationID
        );
        $this->objectAction = new ArticleAction(array(), 'create', $data);
        $resultValues = $this->objectAction->executeAction();

        $this->article = $resultValues['returnValues'];

        if($this->activateArticle) {
            $this->article->getEditor()->setActive();
        }
        else {
            ModerationQueueActivationManager::getInstance()->addModeratedContent('com.woltnet.wiki.article', $this->article->articleID);
        }

        $this->saved();

        MessageQuoteManager::getInstance()->saved();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array(
            'application' => 'wiki',
            'object' => $this->article
        )));
    }
}
