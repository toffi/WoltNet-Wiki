<?php
namespace wiki\data\article\version;

use wiki\system\cache\builder\ArticleVersionCacheBuilder;
use wiki\system\cache\builder\ArticleCacheBuilder;
use wiki\system\cache\builder\ArticlePermissionCacheBuilder;
use wiki\data\article\ArticleEditor;
use wiki\data\article\Article;
use wcf\data\DatabaseObjectEditor;
use wcf\data\conversation\ConversationAction;
use wcf\system\WCF;
use wcf\system\user\activity\event\UserActivityEventHandler;
use wcf\data\IEditableCachedObject;

/**
 * Represents a article label.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2013 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article.version
 * @category WoltNet Wiki
 */
class ArticleVersionEditor extends DatabaseObjectEditor implements IEditableCachedObject {
	/**
	 *
	 * @see wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wiki\data\article\version\ArticleVersion';
	
	/**
	 * Activates given article.
	 */
	public function setActive() {
		$sql = "UPDATE " . self::getDatabaseTableName() . " SET isActive = 0
                WHERE articleID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array (
				$this->articleID 
		));
		
		$sql = "UPDATE " . self::getDatabaseTableName() . " SET isActive = 1
                WHERE versionID = ?";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute(array (
				$this->versionID 
		));
		
		if(MODULE_CONVERSATION && $this->userID != 0 && $this->userID != WCF::getUser()->userID) {
			$data = array (
					'subject' => WCF::getLanguage()->getDynamicVariable('wiki.article.conversation.subject.active', array (
							'topic' => $this->getTitle() 
					)),
					'time' => TIME_NOW,
					'userID' => WCF::getUser()->userID,
					'username' => WCF::getUser()->username,
					'isDraft' => 0 
			);
			$conversationData = array (
					'data' => $data,
					'participants' => array (
							$this->userID 
					),
					'messageData' => array (
							'message' => WCF::getLanguage()->getDynamicVariable('wiki.article.conversation.message.active', array (
									'username' => $this->username,
									'inspectorID' => WCF::getUser()->userID,
									'inspector' => WCF::getUser()->username,
									'articleID' => $this->articleID,
									'topic' => $this->getTitle(),
									'createTime' => $this->time 
							)) 
					) 
			);
			$objectAction = new ConversationAction(array (), 'create', $conversationData);
			$objectAction->executeAction();
		}
		
		$articleEditor = new ArticleEditor(new Article($this->articleID));
		$articleEditor->update(array (
				'activeVersionID' => $this->versionID 
		));
		
		if($this->userID != 0) {
			UserActivityEventHandler::getInstance()->fireEvent('com.woltnet.wiki.article.recentActivityEvent', $this->articleID, $this->getArticle()->languageID, $this->userID, $this->time);
		}
	}
	
	/**
	 *
	 * @see wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		ArticleCacheBuilder::getInstance()->reset();
		ArticleVersionCacheBuilder::getInstance()->reset();
		ArticlePermissionCacheBuilder::getInstance()->reset();
	}
}
