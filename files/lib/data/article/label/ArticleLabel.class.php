<?php
namespace wiki\data\article\label;
use wiki\data\WIKIDatabaseObject;

use wcf\system\WCF;

/**
 * Represents a article label.
 * 
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.conversation.label
 * @category	WoltNet Wiki
 */
class ArticleLabel extends WIKIDatabaseObject {
	/**
	 * @see	wcf\data\DatabaseObject::$databaseTableName
	 */
	protected static $databaseTableName = 'article_label';
	
	/**
	 * @see	wcf\data\DatabaseObject::$databaseIndexName
	 */
	protected static $databaseTableIndexName = 'labelID';
	
	/**
	 * list of pre-defined css class names
	 * @var	array<string>
	 */
	public static $availableCssClassNames = array(
		'yellow',
		'orange',
		'brown',
		'red',
		'pink',
		'purple',
		'blue',
		'green',
		'black',
		
		'none' /* not a real value */
	);
	
	/**
	 * Returns a list of conversation labels for given user id.
	 * 
	 * @param	integer		$userID
	 * @return	wcf\data\conversation\label\ConversationLabelList
	 */
	public static function getLabelsByUser($userID = null) {
		if ($userID === null) $userID = WCF::getUser()->userID;
		
		$labelList = new ArticleLabelList();
		$labelList->getConditionBuilder()->add("article_label.userID = ?", array($userID));
		$labelList->sqlLimit = 0;
		$labelList->readObjects();
		
		return $labelList;
	}
	
	/**
	 * Returns a list of available CSS class names.
	 * 
	 * @return	array<string>
	 */
	public static function getLabelCssClassNames() {
		return self::$availableCssClassNames;
	}
}
