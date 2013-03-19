<?php
namespace wiki\data\article;
use wiki\system\cache\builder\ArticleCacheBuilder;

use wcf\system\cache\CacheHandler;
use wcf\system\SingletonFactory;

/**
 * Manages the article cache.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet Wiki
 */
class ArticleCache extends SingletonFactory {

	/**
	 * cached articles
	 * @var array<wiki\data\article\ViewableArticle>
	 */
	protected $cachedArticles = array();

	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// get article cache
		$this->cachedArticles = ArticleCacheBuilder::getInstance()->getData();
	}

	/**
	 * Gets the article with the given article id from cache.
	 *
	 * @param 	integer		$articleID
	 * @return	wiki\data\article\ViewableArticle
	 */
	public function getArticle($articleID) {
		if (!isset($this->cachedArticles['articles'][$articleID])) {
			return null;
		}

		return $this->cachedArticles['articles'][$articleID];
	}

	/**
	 * Returns a list of all articles.
	 *
	 * @return	array<wiki\data\ViewableArticle>
	 */
	public function getArticles() {
		return $this->cachedArticles['articles'];
	}
}
