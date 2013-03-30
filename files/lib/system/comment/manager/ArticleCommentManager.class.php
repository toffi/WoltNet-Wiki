<?php
namespace wiki\system\comment\manager;
use wiki\data\article\Article;

use wcf\system\comment\manager\AbstractCommentManager;
use wcf\system\WCF;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.comment.manager
 * @category 	WoltNet - Wiki
 */
class ArticleCommentManager extends AbstractCommentManager {
    public $article = null;

    /**
     * @see \wcf\system\comment\manager\AbstractCommentManager::canAdd()
     */
    public function canAdd($objectID) {
        if($this->article === null) {
        	$this->article = new Article($objectID);
        }

        if(!$this->isAccessible($objectID)) {
            return false;
        }

        return $this->article->getPermission('canWriteComment');
    }

    /**
     * @see wcf\system\comment\manager\AbstractCommentManager::getLink()
     */
    public function getLink($objectTypeID, $objectID) {
      return "";
    }

    /**
     * @see wcf\system\comment\manager\AbstractCommentManager::getTitle()
     */
    public function getTitle($objectTypeID, $objectID, $isResponse = false) {
      return "";
    }

    /**
     * @see wcf\system\comment\manager\AbstractCommentManager::isAccessible()
     */
    public function isAccessible($objectID) {
    	if($this->article === null) {
    		$this->article = new Article($objectID);
    	}

    	return $this->article->getPermission('canReadArticle');
    }

    /**
     * @see wcf\system\comment\manager\AbstractCommentManager::updateCounter()
     */
    public function updateCounter($objectID, $value) {

    }
}
