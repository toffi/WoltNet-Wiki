<?php
namespace wiki\data\article\version;

use wiki\data\WIKIDatabaseObject;
use wiki\system\article\ArticlePermissionHandler;
use wiki\data\article\ArticleCache;
use wcf\system\bbcode\MessageParser;
use wcf\util\StringUtil;
use wcf\system\exception\PermissionDeniedException;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\WCF;
use wcf\data\IMessage;
use wcf\system\request\IRouteController;
use wcf\data\ILinkableObject;
use wcf\system\request\LinkHandler;

/**
 *
 * @author Rene Gessinger
 * @copyright 2013 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article.version
 * @category WoltNet - Wiki
 */
class ArticleVersion extends WIKIDatabaseObject implements IRouteController, ILinkableObject, IMessage {

	/**
	 *
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'article_version';

	/**
	 *
	 * @see wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'versionID';

	/**
	 *
	 * @see DatabaseObject::__construct()
	 */
	public function __construct($id, $row = null, $object = null) {
		// we need to overload the constructor for active row
		if($id !== null) {
			$sql = "SELECT	*
                  FROM	" . static::getDatabaseTableName() . "
                  WHERE	(" . static::getDatabaseTableIndexName() . " = ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array (
					$id 
			));
			$row = $statement->fetchArray();
			
			if($row === false)
				$row = array ();
		}
		
		parent::__construct(null, $row, $object);
	}

	/**
	 *
	 * @see wcf\data\IMessage::__toString()
	 */
	public function __toString() {
		return $this->getFormattedMessage();
	}

	public function getEditor() {
		if($this->editor === null) {
			$this->editor = new ArticleVersionEditor($this);
		}
		
		return $this->editor;
	}

	public function getAuthor() {
		if($this->author === null) {
			$this->author = new UserProfile(new User($this->userID));
		}
		
		return $this->author;
	}

	public function getArticle() {
		return ArticleCache::getInstance()->getArticle($this->articleID);
	}

	/**
	 *
	 * @see \wcf\data\IMessage::getExcerpt()
	 */
	public function getExcerpt($maxLength = 255, $highlight = false) {
		if(! $highlight)
			MessageParser::getInstance()->setOutputType('text/plain');
		$message = MessageParser::getInstance()->parse($this->message, false, false, true);
		if(! $highlight) {
			if(StringUtil::length($message) > $maxLength) {
				$message = StringUtil::substring($message, 0, $maxLength) . '&hellip;';
			}
		} else {
			if(StringUtil::length($message) > $maxLength) {
				$message = StringUtil::substring($message, 0, $maxLength);
			}
		}
		
		return $message;
	}

	/**
	 * Checks the given article permissions. Throws a PermissionDeniedException if the active user doesn't have one of the given permissions.
	 *
	 * @param array<string> $permissions        	
	 */
	public function checkPermission(array $permissions = array('canViewArticle')) {
		foreach($permissions as $permission) {
			if(! $this->getPermission($permission)) {
				throw new PermissionDeniedException();
			}
		}
	}

	/**
	 * Checks whether the active user has the permission with the given name on this article.
	 *
	 * @param string $permission
	 *        	the requested permission
	 * @return boolean
	 */
	public function getPermission($permission = 'canViewArticle') {
		return ArticlePermissionHandler::getInstance()->getPermission($this->versionID, $permission);
	}

	/**
	 * Checks whether the active user has the moderator permission with the given name on this article.
	 *
	 * @param string $permission
	 *        	the requested permission
	 * @return boolean
	 */
	public function getModeratorPermission($permission) {
		return ArticlePermissionHandler::getInstance()->getModeratorPermission($this->articleID, $permission);
	}

	/**
	 *
	 * @see wcf\data\IMessage::isVisible()
	 */
	public function isVisible() {
		if($this->isActive == 0) {
			return $this->getModeratorPermission('canReadDeactivatedArticle');
		}
		if($this->isDeleted == 1) {
			return $this->getModeratorPermission('canReadTrashedArticle');
		}
		return $this->getPermission('canViewArticle');
	}

	/**
	 * Returns true, if the active user has the permission to enter this article.
	 *
	 * @return boolean
	 */
	public function canEnter() {
		if($this->isActive == 0) {
			return $this->getModeratorPermission('canReadDeactivatedArticle');
		}
		if($this->isDeleted == 1) {
			return $this->getModeratorPermission('canReadTrashedArticle');
		}
		return ($this->getPermission('canViewArticle') && $this->getPermission('canReadArticle'));
	}

	/**
	 * Returns true, if the active user has the permission to edit this article.
	 *
	 * @return boolean
	 */
	public function isEditable() {
		$ownPermission = false;
		$modPermission = false;
		if(WCF::getUser()->userID == $this->userID) {
			$ownPermission = $this->getPermission('canEditOwnArticle');
		}
		$modPermission = $this->getModeratorPermission('canEditArticle');
		
		return ($ownPermission || $modPermission);
	}

	/**
	 * Returns true, if the active user has the permission to trash this article.
	 *
	 * @return boolean
	 */
	public function isTrashable() {
		if($this->isDeleted == 1)
			return false;
		return $this->getModeratorPermission('canTrashArticle');
	}

	/**
	 * Returns true, if the active user has the permission to delete this article.
	 *
	 * @return boolean
	 */
	public function isDeletable() {
		return $this->getModeratorPermission('canDeleteArticle');
	}

	/**
	 * Returns true, if the active user has the permission to restore this article.
	 *
	 * @return boolean
	 */
	public function isRestorable() {
		if($this->isDeleted == 0)
			return false;
		return $this->getModeratorPermission('canRestoreArticle');
	}

	/**
	 * Returns the formatted Message of this object
	 */
	public function getFormattedMessage() {
		MessageParser::getInstance()->setOutputType('text/html');
		return MessageParser::getInstance()->parse($this->message, $this->enableSmilies, $this->enableHtml, $this->enableBBCodes);
	}

	/**
	 *
	 * @see wcf\data\IMessage::getMessage()
	 */
	public function getMessage() {
		return $this->message;
	}

	/**
	 *
	 * @see wcf\system\request\IRouteController::getTitle()
	 */
	public function getTitle() {
		return $this->subject;
	}

	/**
	 *
	 * @see wcf\data\ILinkableObject::getLink()
	 */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('ArticleVersion', array (
				'application' => 'wiki',
				'object' => $this 
		));
	}

	/**
	 *
	 * @see wcf\data\IUserContent::getTime()
	 */
	public function getTime() {
		return $this->time;
	}

	/**
	 *
	 * @see wcf\data\IUserContent::getUserID()
	 */
	public function getUserID() {
		return $this->userID;
	}

	/**
	 *
	 * @see wcf\data\IUserContent::getUsername()
	 */
	public function getUsername() {
		return $this->username;
	}
}