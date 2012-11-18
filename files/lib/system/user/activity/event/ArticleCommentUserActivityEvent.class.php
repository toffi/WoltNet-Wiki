<?php
namespace wiki\system\user\activity\event;
use wiki\data\article\ArticleList;

use wcf\data\comment\CommentList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * User activity event implementation for article reviews.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.user.activity.event
 * @category 	WoltNet Wiki
 */
class ArticleCommentUserActivityEvent extends SingletonFactory implements IUserActivityEvent {
	/**
	 * @see	wcf\system\user\activity\event\IUserActivityEvent::prepare()
	 */
	public function prepare(array $events) {
		$comentIDs = array();
		foreach ($events as $event) {
			$comentIDs[] = $event->objectID;
		}

		// fetch comments
		$commentList = new CommentList();
		$commentList->getConditionBuilder()->add("comment.commentID IN (?)", array($comentIDs));
		$commentList->sqlLimit = 0;
		$commentList->readObjects();
		$comments = $commentList->getObjects();

		// fetch users
		$articleIDs = array();
		foreach ($comments as $comment) {
			$articleIDs[] = $comment->objectID;
		}

		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add("article.articleID IN (?)", array($articleIDs));
		$articleList->sqlLimit = 0;
		$articleList->readObjects();
		$articles = $articleList->getObjects();

		// set message
		foreach ($events as $event) {
			if (isset($comments[$event->objectID])) {
				// short output
				$comment = $comments[$event->objectID];
				if (isset($articles[$comment->objectID])) {
					$article = $articles[$comment->objectID];
					$text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.articleComment', array('article' => $article));
					$event->setTitle($text);

					// output
					$event->setDescription($comment->getFormattedMessage());
				}
			}
		}
	}
}
