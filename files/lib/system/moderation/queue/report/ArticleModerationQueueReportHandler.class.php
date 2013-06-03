<?php
namespace wiki\system\moderation\queue\report;

use wiki\system\moderation\queue\AbstractArticleModerationQueueHandler;
use wiki\data\article\ArticleCache;
use wiki\data\article\ViewableArticle;
use wcf\system\moderation\queue\report\IModerationQueueReportHandler;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\WCF;

/**
 * An implementation of IModerationQueueReportHandler for articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.moderation.queue
 * @category WoltNet - Wiki
 */
class ArticleModerationQueueReportHandler extends AbstractArticleModerationQueueHandler implements IModerationQueueReportHandler {
	/**
	 *
	 * @see wcf\system\moderation\queue\AbstractModerationQueueHandler::$definitionName
	 */
	protected $definitionName = 'com.woltlab.wcf.moderation.report';
	
	/**
	 *
	 * @see wcf\system\moderation\queue\AbstractModerationQueueHandler::$objectType
	 */
	protected $objectType = 'com.woltnet.wiki.article';
	
	/**
	 *
	 * @see wcf\system\moderation\queue\IModerationQueueHandler::assignQueues()
	 */
	public function assignQueues(array $queues) {
		$assignments = array ();
		foreach($queues as $queue) {
			$assignUser = 0;
			if(ArticleCache::getInstance()->getArticle($queue->objectID)->getCategory()->getPermission('canModerateArticle')) {
				$assignUser = 1;
			}
			
			$assignments[$queue->queueID] = $assignUser;
		}
		
		ModerationQueueManager::getInstance()->setAssignment($assignments);
	}
	
	/**
	 *
	 * @see wcf\system\moderation\queue\report\IModerationQueueReportHandler::canReport()
	 */
	public function canReport($objectID) {
		if(! $this->isValid($objectID)) {
			return false;
		}
		
		return true;
	}
	
	/**
	 *
	 * @see wcf\system\moderation\queue\report\IModerationQueueReportHandler::getReportedContent()
	 */
	public function getReportedContent(ViewableModerationQueue $queue) {
		WCF::getTPL()->assign(array (
				'article' => new ViewableArticle($queue->getAffectedObject()) 
		));
		
		return WCF::getTPL()->fetch('moderationArticle', 'wiki');
	}
	
	/**
	 *
	 * @see wcf\system\moderation\queue\report\IModerationQueueReportHandler::getReportedObject()
	 */
	public function getReportedObject($objectID) {
		if($this->isValid($objectID)) {
			return $this->getArticle($objectID);
		}
		
		return null;
	}
}
