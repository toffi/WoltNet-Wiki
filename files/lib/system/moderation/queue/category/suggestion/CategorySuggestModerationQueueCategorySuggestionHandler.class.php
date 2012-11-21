<?php
namespace wiki\system\moderation\queue\category\suggestion;
use wiki\data\category\suggestion\CategorySuggestion;
use wiki\data\category\suggestion\CategorySuggestionList;

use wcf\system\moderation\queue\category\suggestion\IModerationQueueCategorySuggestionHandler;
use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\exception\SystemException;
use wcf\system\moderation\queue\ModerationQueueManager;
use wcf\system\WCF;

/**
 * An implementation of IModerationQueueCategorySuggestionHandler for category suggestions.
 * 
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.moderation.queue
 * @category	WoltNet Wiki
 */
class CategorySuggestModerationQueueCategorySuggestionHandler implements IModerationQueueCategorySuggestionHandler {
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::assignQueues()
	 */
	public function assignQueues(array $queues) {
		
	}
	
	/**
	 * @see	wcf\system\moderation\queue\report\IModerationQueueReportHandler::canReport()
	 */
	public function canReport($objectID) {
		
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::getContainerID()
	 */
	public function getContainerID($objectID) {
		return 0;
	}
	
	/**
	 * @see	wcf\system\moderation\queue\report\IModerationQueueReportHandler::getReportedContent()
	 */
	public function getReportedContent(ViewableModerationQueue $queue) {
		
	}
	
	/**
	 * @see	wcf\system\moderation\queue\report\IModerationQueueReportHandler::getReportedObject()
	 */
	public function getReportedObject($objectID) {
		
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::isValid()
	 */
	public function isValid($objectID) {
		
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::populate()
	 */
	public function populate(array $queues) {

	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::removeContent()
	 */
	public function removeContent(ModerationQueue $queue, $message) {

	}
}
