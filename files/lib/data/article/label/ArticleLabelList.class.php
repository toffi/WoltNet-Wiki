<?php
namespace wiki\data\article\label;
use wcf\data\DatabaseObjectList;

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
class ArticleLabelList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wiki\data\article\label\ArticleLabel';
}
