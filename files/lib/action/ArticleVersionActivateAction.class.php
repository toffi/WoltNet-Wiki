<?php
namespace wiki\action;
use wiki\data\article\ArticleEditor;
use wiki\data\article\ArticleCache;

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

        if(isset($_GET['id'])) $this->articleID = intval($_GET['id']);

        $this->readData();
    }

    public function readData() {
        $this->article = ArticleCache::getInstance()->getArticle($this->articleID);

        if(!$this->article->articleID) {
            throw new IllegalLinkException();
        }

        if(!$this->article->getAtiveVersion()->getModeratorPermission()) {
            throw new PermissionDeniedException();
        }
    }

    /**
     * @see wcf\action\AbstractAction::execute()
     */
    public function execute() {
        parent::execute();
        $editor = $this->article->getEditor();
        $editor->setActive();
        ArticleEditor::resetCache();

        $moderationManager = ModerationQueueActivationManager::getInstance()->removeModeratedContent('com.woltnet.wiki.article', array($this->articleID));
        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array(
                'application' => 'wiki',
                'object' => $this->article
        )));
    }
}
