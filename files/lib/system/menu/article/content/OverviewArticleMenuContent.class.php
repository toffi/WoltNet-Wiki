<?php
namespace wiki\system\menu\article\content;
use wiki\data\article\ArticleCache;

use wcf\system\menu\article\content\IArticleMenuContent;
use wcf\system\event\EventHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.menu.article.content
 * @category 	WoltNet - Wiki
 */
class OverviewArticleMenuContent extends SingletonFactory implements IArticleMenuContent {
	/**
	 * @see	wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		EventHandler::getInstance()->fireAction($this, 'shouldInit');

		EventHandler::getInstance()->fireAction($this, 'didInit');
	}

	/**
	 * @see	wiki\system\menu\article\content\IArticleMenuContent::getContent()
	 */
	public function getContent($articleID) {
		$article = ArticleCache::getInstance()->getArticle($articleID);

		WCF::getTPL()->assign(array(
			'article' 	=> $article
		));
		return WCF::getTPL()->fetch('articleOverview', 'wiki');
	}
}
