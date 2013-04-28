<?php
namespace wiki\system\moderation\queue\activation;
use wiki\data\article\ViewableArticle;
use wiki\system\moderation\queue\AbstractArticleModerationQueueHandler;

use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\moderation\queue\activation\IModerationQueueActivationHandler;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;

/**
 * An implementation of IModerationQueueActivationHandler for articles.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.moderation.queue.activation
 * @category 	WoltNet Wiki
 */
class ArticleModerationQueueActivationHandler extends AbstractArticleModerationQueueHandler implements IModerationQueueActivationHandler {
    /**
     * @see	wcf\system\moderation\queue\AbstractModerationQueueHandler::$definitionName
     */
    protected $definitionName = 'com.woltlab.wcf.moderation.activation';

    /**
     * @see	wcf\system\moderation\queue\AbstractModerationQueueHandler::$objectType
     */
    protected $objectType = 'com.woltnet.wiki.article';

    /**
     * @see	wcf\system\moderation\queue\IModerationQueueHandler::assignQueues()
     */
    public function assignQueues(array $queues) {
        $assignments = array();
        foreach ($queues as $queue) {
            $assignUser = 0;
            if (WCF::getSession()->getPermission('mod.wiki.article.canActivateArticle')) {
                $assignUser = 1;
            }

            $assignments[$queue->queueID] = $assignUser;
        }

        ModerationQueueManager::getInstance()->setAssignment($assignments);
    }

    /**
     * @see	wcf\system\moderation\queue\activation\IModerationQueueActivationHandler::enableContent()
     */
    public function enableContent(ModerationQueue $queue) {
        if ($this->isValid($queue->objectID) && !$this->getArticle($queue->objectID)->isActive) {
            //$articleAction = new ArticleAction(array($this->getArticle($queue->objectID)), 'setActive');
            //$articleAction->executeAction();
            $editor = $this->getArticle($queue->objectID)->getEditor();
            $editor->setActive();
            $editor->resetCache();
        }
    }

    /**
     * @see	wcf\system\moderation\queue\activation\IModerationQueueActivationHandler::getDisabledContent()
     */
    public function getDisabledContent(ViewableModerationQueue $queue) {
        WCF::getTPL()->assign(array(
                'article' => new ViewableArticle($queue->getAffectedObject())
        ));

        return WCF::getTPL()->fetch('moderationArticle', 'wiki');
    }
}
