<?php
namespace wiki\system\moderation\queue\category\suggestion;
use wiki\data\category\suggestion\CategorySuggestionAction;

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
		$assignments = array();
		foreach ($queues as $queue) {
			$assignUser = false;
			if (WCF::getSession()->getPermission('mod.wiki.category.canManageSuggestedCategories')) {
				$assignUser = true;
			}
		
			$assignments[$queue->queueID] = $assignUser;
		}
		
		ModerationQueueManager::getInstance()->setAssignment($assignments);
	}
	
	/**
	 * @see	wcf\system\moderation\queue\report\IModerationQueueReportHandler::canReport()
	 */
	public function canSuggest($objectID) {
		if (!$this->isValid($objectID)) {
			return false;
		}
		
		return true;
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
	public function getSuggestedContent(ViewableModerationQueue $queue) {
		WCF::getTPL()->assign(array(
				'article' => $queue->getAffectedObject()
		));
		
		return WCF::getTPL()->fetch('moderationCategorySuggestion', 'wcf');
	}
	
	/**
	 * @see	wcf\system\moderation\queue\report\IModerationQueueReportHandler::getReportedObject()
	 */
	public function getSuggestedObject($objectID) {
		if ($this->isValid($objectID)) {
			return $this->getCategorySuggestion($objectID);
		}
		
		return null;
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::isValid()
	 */
	public function isValid($objectID) {
		if ($this->getCategorySuggestion($objectID) === null) {
			return false;
		}

		return true;
	}
	
	/**
	 * Returns a categorySuggestion object by suggestion id or null if suggestion id is invalid.
	 *
	 * @param	integer		$objectID
	 * @return	wiki\data\category\suggestion\CategorySuggestion
	 */
	protected function getCategorySuggestion($objectID) {
		$object = new CategorySuggestion($objectID);
		
		return $object;
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::populate()
	 */
	public function populate(array $queues) {
		$objectIDs = array();
		foreach ($queues as $object) {
			$objectIDs[] = $object->objectID;
		}
		
		// fetch articles
		$categorySuggestionList = new CategorySuggestionList();
		$categorySuggestionList->getConditionBuilder()->add("category_suggestion.suggestionID IN (?)", array($objectIDs));
		$categorySuggestionList->sqlLimit = 0;
		$categorySuggestionList->readObjects();
		$categorySuggestions = $categorySuggestionList->getObjects();
		
		foreach ($queues as $object) {
			if (isset($categorySuggestions[$object->objectID])) {
				$categorySuggestion = $categorySuggestions[$object->objectID];
		
				$object->setAffectedObject($categorySuggestion);
			}
		}
	}
	
	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::removeContent()
	 */
	public function removeContent(ModerationQueue $queue, $message) {
		$this->objectAction = new CategorySuggestionAction(array($queue->getAffectedObject()->suggestionID), 'delete');
		$this->objectAction->executeAction();
	}
}
