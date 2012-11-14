<?php
namespace wiki\data\article;

/**
 * Represents a list of search results.
 *
 * @author	René Gessinger (NurPech)
 * @copyright	2012 WoltNet
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
		$this->sqlSelects .= 'category.categoryID, category.categoryName';
		$this->sqlJoins .= " LEFT JOIN wiki".WIKI_N."_category category ON (category.categoryID = article.categoryID)";
	}
}
