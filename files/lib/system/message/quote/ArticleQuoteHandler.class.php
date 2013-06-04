<?php
namespace wiki\system\message\quote;

use wiki\data\article\ArticleList;
use wcf\system\message\quote\AbstractMessageQuoteHandler;
use wcf\system\message\quote\QuotedMessage;
use wcf\system\message\quote\MessageQuoteManager;

/**
 * IMessageQuoteHandler implementation for articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.message.quote
 * @category WoltNet Wiki
 */
class ArticleQuoteHandler extends AbstractMessageQuoteHandler {

	/**
	 *
	 * @see wcf\system\message\quote\AbstractMessageQuoteHandler::getMessages()
	 */
	protected function getMessages(array $data) {
		// read articles
		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add("article.articleID IN (?)", array (
				array_keys($data) 
		));
		$articleList->sqlLimit = 0;
		$articleList->readObjects();
		$articles = $articleList->getObjects();
		
		// create QuotedMessage objects
		$quotedArticles = array ();
		foreach($articles as $article) {
			$message = new QuotedMessage($article);
			
			foreach(array_keys($data[$article->articleID]) as $quoteID) {
				$message->addQuote($quoteID, MessageQuoteManager::getInstance()->getQuote($quoteID, false), 				// single
				                                                                                            // quote
				                                                                                            // or
				                                                                                            // excerpt
				MessageQuoteManager::getInstance()->getQuote($quoteID, true)); // same
					                                                               // as
					                                                               // above
					                                                               // or
					                                                               // full
					                                                               // quote
			}
			
			$quotedArticles[] = $message;
		}
		
		return $quotedArticles;
	}
}
