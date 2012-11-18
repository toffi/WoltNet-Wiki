<?php
namespace wiki\data\article;

/**
 * Represents a list of watch articles.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.project
 * @category 	WoltNet Wiki
 */
class WatchedArticleList extends ViewableArticleList {

	/**
	 * @see wiki\data\project\ViewableArticleList::$decoratorClassName
	 */
	public $decoratorClassName = 'wiki\data\article\WatchedArticle';
}
