<?php
namespace wiki\data\article;

/**
 * Represents a list of search results.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class SearchResultArticleList extends ArticleList {

	/**
	 * @see wiki\data\article\ViewableArticleList::$decoratorClassName
	 */
	public $decoratorClassName = 'wiki\data\article\SearchResultArticle';

	/**
	 * Creates a new SearchResultArticleList object.
	 */
	public function __construct() {
		parent::__construct();

		if (!empty($this->sqlSelects)) $this->sqlSelects .= ',';
		$this->sqlSelects .= 'category.categoryID, category.title';
		$this->sqlJoins .= " LEFT JOIN wcf".WCF_N."_category category ON (category.categoryID = article.categoryID)";
	}
}
