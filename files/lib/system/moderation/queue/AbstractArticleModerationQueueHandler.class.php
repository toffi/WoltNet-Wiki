<?php
namespace wiki\system\moderation\queue;
use wiki\system\moderation\queue;
use wiki\data\article\ArticleList;
use wiki\data\article\Article;

use wcf\data\moderation\queue\ModerationQueue;
use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\exception\SystemException;
use wcf\system\moderation\queue\IModerationQueueHandler;
use wcf\system\WCF;

/**
 * An abstract implementation of IModerationQueueHandler for articles.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @package	com.woltnet.wiki
 * @subpackage	system.moderation.queue
 * @category 	WoltNet Wiki
 */
abstract class AbstractArticleModerationQueueHandler implements IModerationQueueHandler {
	/**
	 * list of articles
	 * @var	array<wiki\data\article\Article>
	 */
	protected static $articles = array();

	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::getContainerID()
	 */
	public function getContainerID($objectID) {
		$categoryID = $this->getArticle($objectID)->categoryID;

		return $categoryID;
	}


	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::isValid()
	 */
	public function isValid($objectID) {
		if ($this->getArticle($objectID) === null) {
			return false;
		}

		return true;
	}

	/**
	 * Returns a article object by article id or null if article id is invalid.
	 *
	 * @param	integer		$objectID
	 * @return	wiki\data\article\Article
	 */
	protected function getArticle($objectID) {
		if (!array_key_exists($objectID, self::$articles)) {
			self::$articles[$objectID] = new Article($objectID);
			if (!self::$articles[$objectID]->articleID) {
				self::$articles[$objectID] = null;
			}
		}

		return self::$articles[$objectID];
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
		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add("article.articleID IN (?)", array($objectIDs));
		$articleList->sqlLimit = 0;
		$articleList->readObjects();
		$articles = $articleList->getObjects();

		foreach ($queues as $object) {
			if (isset($articles[$object->objectID])) {
				$article = $articles[$object->objectID];

				$object->setAffectedObject($article);
			}
		}
	}

	/**
	 * @see	wcf\system\moderation\queue\IModerationQueueHandler::removeContent()
	 */
	public function removeContent(ModerationQueue $queue, $message) {
		throw new SystemException("Article cannot be deleted");
	}
}
