<?php
namespace wiki\action;
use wiki\data\article\ArticleCache;
use wiki\data\article\version\ArticleVersionEditor;

use wcf\action\AbstractAction;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\moderation\queue\ModerationQueueActivationManager;

/**
 * This class activates the given ArticleVersion
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	action
 * @category 	WoltNet - Wiki
 */
class ArticleVersionAction extends AbstractAction {
    public $articleVersionID = 0;

    public $articleVersion = null;

    public $action = null;

    /**
     * @see wiki\action\AbstractAction::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        if(isset($_GET['id'])) $this->articleVersionID = intval($_GET['id']);
        if(isset($_GET['action'])) $this->action = escapeString($_GET['action']);

        $this->readData();
    }

    public function readData() {
        $this->articleVersion = ArticleCache::getInstance()->getArticleVersion($this->articleVersionID);

        if(!$this->articleVersion->versionID) {
            throw new IllegalLinkException();
        }

        if(!$this->articleVersion->getModeratorPermission()) {
            throw new PermissionDeniedException();
        }
    }

    public function activate() {
    	$editor = $this->articleVersion->getEditor();
    	$editor->setActive();
    	ArticleVersionEditor::resetCache();

    	$moderationManager = ModerationQueueActivationManager::getInstance()->removeModeratedContent('com.woltnet.wiki.article', array($this->articleVersionID));
    	HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array(
	    	'application' => 'wiki',
	    	'categoryName'	=> $this->articleVersion->getArticle()->getCategory()->getTitle(),
	    	'object' => $this->articleVersion->getArticle(),
	    	'versionID'	=> $this->articleVersionID
    	)));
    }

    public function trash() {
    	$this->objectAction = new ArticleVersionAction(array($this->article->articleID), 'trash');
    	$this->objectAction->validateAction();
    	$this->objectAction->executeAction();

    	HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Category', array(
	    	'application' => 'wiki',
	    	'object' => $this->article->getCategory()
    	)));
    }

    public function restore() {

    }

    public function delete() {

    }

    /**
     * @see wcf\action\AbstractAction::execute()
     */
    public function execute() {
        parent::execute();

        switch ($this->action) {
        	case 'avtivate': 	$this->activate(); break;
        	case 'trash':		$this->trash(); break;
        	case 'restore':		$this->restore(); break;
        	case 'delete':		$this->delete(); break;
        	default: break;
        }
    }
}
