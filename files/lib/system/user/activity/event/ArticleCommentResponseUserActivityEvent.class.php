<?php
namespace wiki\system\user\activity\event;

use wiki\data\article\ArticleList;
use wcf\data\comment\response\CommentResponseList;
use wcf\data\comment\CommentList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * User activity event implementation for article comment responses.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @package com.woltnet.wiki
 * @subpackage system.user.activity.event
 * @category WoltNet Wiki
 */
class ArticleCommentResponseUserActivityEvent extends SingletonFactory implements IUserActivityEvent {
	/**
	 *
	 * @see wcf\system\user\activity\event\IUserActivityEvent::prepare()
	 */
	public function prepare(array $events) {
		$responseIDs = array ();
		foreach($events as $event) {
			$responseIDs[] = $event->objectID;
		}
		
		// fetch responses
		$responseList = new CommentResponseList();
		$responseList->getConditionBuilder()->add("comment_response.responseID IN (?)", array (
				$responseIDs 
		));
		$responseList->sqlLimit = 0;
		$responseList->readObjects();
		$responses = $responseList->getObjects();
		
		// fetch comments
		$commentIDs = array ();
		foreach($responses as $response) {
			$commentIDs[] = $response->commentID;
		}
		$commentList = new CommentList();
		$commentList->getConditionBuilder()->add("comment.commentID IN (?)", array (
				$commentIDs 
		));
		$commentList->sqlLimit = 0;
		$commentList->readObjects();
		$comments = $commentList->getObjects();
		
		// fetch articles
		$articleIDs = array ();
		foreach($comments as $comment) {
			$articleIDs[] = $comment->objectID;
		}
		
		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add("article.articleID IN (?)", array (
				$articleIDs 
		));
		$articleList->sqlLimit = 0;
		$articleList->readObjects();
		$articles = $articleList->getObjects();
		
		// set message
		foreach($events as $event) {
			if(isset($responses[$event->objectID])) {
				$response = $responses[$event->objectID];
				if(isset($comments[$response->commentID])) {
					$comment = $comments[$response->commentID];
					if(isset($articles[$comment->objectID]) && isset($articles[$comment->userID])) {
						// title
						$text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.wikiArticleCommentResponse', array (
								'commentAuthor' => $articles[$comment->userID],
								'article' => $articles[$comment->objectID] 
						));
						$event->setTitle($text);
						
						// description
						$event->setDescription($response->getFormattedMessage());
					}
				}
			}
		}
	}
}
