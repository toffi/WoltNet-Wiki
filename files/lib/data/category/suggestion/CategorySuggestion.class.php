<?php
namespace wiki\data\category\suggestion;
use wiki\data\WIKIDatabaseObject;

use wcf\data\IUserContent;
use wcf\util\StringUtil;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category.suggestion
 * @category 	WoltNet - Wiki
 */
class CategorySuggestion extends WIKIDatabaseObject implements IUserContent {
	protected $editor = null;
	
	/**
	 * @see wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'category_suggestion';
	
	/**
	 * @see wcf\data\DatabaseObject::$databaseTableIndexName
	 */
	protected static $databaseTableIndexName = 'suggestionID';
	
	/**
	 * @see DatabaseObject::__construct()
	 */
	public function __construct($id, $row = null, $object = null) {
		//we need to overload the constructor for active row
		if ($id !== null) {
			$sql = "SELECT	*
			FROM	".static::getDatabaseTableName()."
			WHERE	(".static::getDatabaseTableIndexName()." = ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array($id));
			$row = $statement->fetchArray();
	
			if ($row === false) $row = array();
		}
	
		parent::__construct(null, $row, $object);
	}
	
	/**
	 * Returns a CategorySuggestionEditor
	 * 
	 * @return wiki\data\category\suggestion\CategorySuggestionEditor
	 */
	public function getEditor() {
		if ($this->editor === null) {
			$this->editor = new CategorySuggestionEditor($this);
		}
	
		return $this->editor;
	}
	
	/**
	 * Returns an excerpt of this article.
	 *
	 * @param	string		$maxLength
	 * @return	string
	 */
	public function getExcerpt($maxLength = 255) {
		if (StringUtil::length($this->description) > $maxLength) {
			$message = StringUtil::encodeHTML(StringUtil::substring($this->description, 0, $maxLength)).'&hellip;';
		}
		else {
			$message = StringUtil::encodeHTML($message);
		}
	
		return $message;
	}
}
