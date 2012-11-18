<?php
namespace wiki\data\article;
use wcf\data\DatabaseObjectList;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class ArticleList extends DatabaseObjectList {
	
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wiki\data\article\Article';
}
