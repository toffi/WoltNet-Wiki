<?php
namespace wiki\data\article\version;
use wiki\system\cache\builder\ArticleVersionCacheBuilder;
use wiki\system\cache\builder\ArticleCacheBuilder;
use wiki\system\cache\builder\ArticlePermissionCacheBuilder;

use wcf\data\DatabaseObjectEditor;
use wcf\data\conversation\ConversationAction;
use wcf\system\WCF;
use wcf\data\IEditableCachedObject;

/**
 * Represents a article label.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2013 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.version
 * @category	WoltNet Wiki
 */
class ArticleVersionEditor extends DatabaseObjectEditor implements IEditableCachedObject {
    /**
     * @see	wcf\data\DatabaseObjectEditor::$baseClass
     */
    protected static $baseClass = 'wiki\data\article\version\ArticleVersion';

    /**
     * Activates given article.
     */
    public function setActive() {
        $where = "";
        if($this->parentID != 0) {
            $where = "parentID = '".$this->parentID."' OR
                    articleID = '".$this->parentID."'";
        } else {
            $where = "articleID = '".$this->articleID."' OR
                    parentID = '".$this->articleID."'";
        }
        $sql = "UPDATE ".self::getDatabaseTableName()." SET isActive = 0
                WHERE ".$where;
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute();

        $sql = "UPDATE ".self::getDatabaseTableName()." SET isActive = 1
                WHERE articleID = ?";
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute(array($this->articleID));

        if(MODULE_CONVERSATION && $this->userID != 0 && $this->userID != WCF::getUser()->userID) {
            $data = array(
                    'subject' => WCF::getLanguage()->getDynamicVariable('wiki.article.conversation.subject.active', array('topic' => $this->getTitle())),
                    'time' => TIME_NOW,
                    'userID' => WCF::getUser()->userID,
                    'username' => WCF::getUser()->username,
                    'isDraft' => 0
            );
            $conversationData = array(
                    'data' => $data,
                    'participants' => array($this->userID),
                    'messageData' => array(
                            'message' => WCF::getLanguage()->getDynamicVariable('wiki.article.conversation.message.active', array(
                                    'username' 	=> $this->username,
                                    'inspectorID'	=> WCF::getUser()->userID,
                                    'inspector'	=> WCF::getUser()->username,
                                    'articleID'	=> $this->articleID,
                                    'topic'		=> $this->getTitle(),
                                    'createTime'	=> $this->time))
                    )
            );
            $objectAction = new ConversationAction(array(), 'create', $conversationData);
            $objectAction->executeAction();
        }
    }

    /**
     * @see	wcf\data\IEditableCachedObject::resetCache()
     */
    public static function resetCache() {
        ArticleCacheBuilder::getInstance()->reset();
        ArticleVersionCacheBuilder::getInstance()->reset();
        ArticlePermissionCacheBuilder::getInstance()->reset();
    }
}