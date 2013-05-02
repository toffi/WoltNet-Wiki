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
class ArticleVersionActivateAction extends AbstractAction {
    public $articleVersionID = 0;

    public $articleVersion = null;

    /**
     * @see wiki\action\AbstractAction::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        if(isset($_GET['id'])) $this->articleVersionID = intval($_GET['versionID']);

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

    /**
     * @see wcf\action\AbstractAction::execute()
     */
    public function execute() {
        parent::execute();
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
}
