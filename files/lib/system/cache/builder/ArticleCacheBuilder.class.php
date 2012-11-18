<?php
namespace wiki\system\cache\builder;
use wiki\data\article\ViewableArticle;
use wiki\data\article\Article;

use wcf\system\event\EventHandler;
use wcf\system\WCF;
use wcf\util\StringUtil;
use wcf\system\cache\builder\ICacheBuilder;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.cache.builder
 * @category 	WoltNet - Wiki
 */
class ArticleCacheBuilder implements ICacheBuilder {
	/**
	 * @see wcf\system\cache\ICacheBuilder::getData()
	 */
	public function getData(array $cacheResource) {
		$data = array(
			'articles' => array()
		);

		$sql = "SELECT
				articleID
			FROM
				wiki".WIKI_N."_article";
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute();
		while($row = $statement->fetchArray()) {
			$data['articles'][$row['articleID']] = ViewableArticle::getArticle($row['articleID']);
		}

		return $data;
	}
}
