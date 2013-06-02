<?php
namespace wiki\system\comment\manager;
use wiki\data\article\version\ArticleVersion;

use wcf\data\comment\response\CommentResponse;
use wcf\data\comment\Comment;
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
     * @see wcf\system\comment\manager\ICommentManager::canAdd()
     */
    public function canAdd($objectID) {
        if(!$this->isAccessible($objectID)) {
            return false;
        }

        if($this->article === null) {
            $this->article = new ArticleVersion($objectID);
        }

        return $this->article->getPermission('canWriteComment');
    }

    /**
     * @see	wcf\system\comment\manager\ICommentManager::canEditComment()
     */
    public function canEditComment(Comment $comment) {
        // disallow guests
        if (!WCF::getUser()->userID) {
            return false;
        }

        if(!$this->isAccessible($comment->objectID)) {
            return false;
        }

        if($this->article === null) {
            $this->article = new ArticleVersion($comment->objectID);
        }

        if($comment->userID == WCF::getUser()->userID) {
            return $this->article->getPermission('canEditOwnComment');
        }
        return $this->article->getPermission('canEditComment');
    }

    /**
     * @see	wcf\system\comment\manager\ICommentManager::canEditCommentResponse()
     */
    public function canEditCommentResponse(CommentResponse $response) {
        // disallow guests
        if (!WCF::getUser()->userID) {
            return false;
        }

        if(!$this->isAccessible($response->getCommenct()->objectID)) {
            return false;
        }

        if($this->article === null) {
            $this->article = new ArticleVersion($response->getCommenct()->objectID);
        }

        if($response->getCommenct()->userID == WCF::getUser()->userID) {
            return $this->article->getPermission('canEditOwnComment');
        }
        return $this->article->getPermission('canEditComment');
    }

    /**
     * @see	wcf\system\comment\manager\ICommentManager::canDeleteComment()
     */
    public function canDeleteComment(Comment $comment) {
        // disallow guests
        if (!WCF::getUser()->userID) {
            return false;
        }

        if(!$this->isAccessible($comment->objectID)) {
            return false;
        }

        if($this->article === null) {
            $this->article = new ArticleVersion($comment->objectID);
        }

        if($comment->userID == WCF::getUser()->userID) {
            return $this->article->getPermission('canDeleteOwnComment');
        }
        return $this->article->getModeratorPermission('canDeleteComment');
    }

    /**
     * @see	wcf\system\comment\manager\ICommentManager::canDeleteCommentResponse()
     */
    public function canDeleteCommentResponse(CommentResponse $response) {
        // disallow guests
        if (!WCF::getUser()->userID) {
            return false;
        }

        if(!$this->isAccessible($response->getCommenct()->objectID)) {
            return false;
        }

        if($this->article === null) {
            $this->article = new ArticleVersion($response->getCommenct()->objectID);
        }

        if($response->getCommenct()->userID == WCF::getUser()->userID) {
            return $this->article->getPermission('canDeleteOwnComment');
        }
        return $this->article->getModeratorPermission('canDeleteComment');
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
    public function isAccessible($objectID, $validateWritePermission = false) {
        if($this->article === null) {
            $this->article = new ArticleVersion($objectID);
        }

        if($validateWritePermission) {
            return $this->article->getPermission('canWriteComment');
        }

        return $this->article->getPermission('canReadArticle');
    }

    /**
     * @see wcf\system\comment\manager\AbstractCommentManager::updateCounter()
     */
    public function updateCounter($objectID, $value) {

    }
}
