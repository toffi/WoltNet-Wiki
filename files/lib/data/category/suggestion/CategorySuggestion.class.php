<?php
namespace wiki\data\category\suggestion;

use wiki\data\WIKIDatabaseObject;
use wcf\data\IUserContent;
use wcf\data\user\User;
use wcf\data\user\UserProfile;
use wcf\system\request\IRouteController;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.category.suggestion
 * @category WoltNet - Wiki
 */
class CategorySuggestion extends WIKIDatabaseObject implements IRouteController, IUserContent {
	protected $editor = null;
	
	/**
	 *
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'category_suggestion';
	
	/**
	 *
	 * @see wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'suggestionID';
	
	/**
	 * user profile object
	 *
	 * @var wcf\data\user\UserProfile
	 */
	protected $userProfile = null;
	
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
	 * Returns a CategorySuggestionEditor
	 *
	 * @return wiki\data\category\suggestion\CategorySuggestionEditor
	 */
	public function getEditor() {
		if($this->editor === null) {
			$this->editor = new CategorySuggestionEditor($this);
		}
		
		return $this->editor;
	}
	
	/**
	 * Returns an excerpt of this article.
	 *
	 * @param string $maxLength        	
	 * @return string
	 */
	public function getExcerpt($maxLength = 255) {
		if(StringUtil::length($this->getReason()) > $maxLength) {
			$message = StringUtil::encodeHTML(StringUtil::substring($this->getReason(), 0, $maxLength)) . '&hellip;';
		} else {
			$message = StringUtil::encodeHTML($this->getReason());
		}
		
		return $message;
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
	
	/**
	 *
	 * @see wcf\system\request\IRouteController::getID()
	 */
	public function getID() {
		return $this->suggestionID;
	}
	
	/**
	 *
	 * @see wcf\system\request\IRouteController::getTitle()
	 */
	public function getTitle() {
		return WCF::getLanguage()->get($this->title);
	}
	
	/**
	 * Returns the reason of this category suggestion.
	 *
	 * @return string
	 */
	public function getReason() {
		return WCF::getLanguage()->get($this->reason);
	}
	
	/**
	 *
	 * @see wcf\data\ILinkableDatabaseObject::getLink()
	 */
	public function getLink() {
		return LinkHandler::getInstance()->getLink('CategorySuggestion', array (
				'object' => $this 
		));
	}
	
	/**
	 * Returns the user profile object.
	 *
	 * @return wcf\data\user\UserProfile
	 */
	public function getUserProfile() {
		if($this->userProfile === null) {
			$this->userProfile = new UserProfile(new User($this->userID));
		}
		
		return $this->userProfile;
	}
}
