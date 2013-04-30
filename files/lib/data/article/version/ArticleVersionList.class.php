<?php
namespace wiki\data\article\version;

use wcf\data\DatabaseObjectList;

/**
 * @author	Rene Gessinger
 * @copyright	2013 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.version
 * @category 	WoltNet - Wiki
 */
class ArticleVersionList extends DatabaseObjectList {
	/**
	 * @see	wcf\data\DatabaseObjectList::$className
	 */
	public $className = 'wiki\data\article\version\ArticleVersion';
}