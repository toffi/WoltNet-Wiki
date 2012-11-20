<?php
namespace wiki\system\user\online\location;
use wiki\data\category\WikiCategory;
use wiki\data\article\ArticleList;

use wcf\data\user\online\UserOnline;
use wcf\system\user\online\location\IUserOnlineLocation;
use wcf\system\WCF;

/**
 * Implementation of IUserOnlineLocation for the article page location.
 *
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.user.online.location
 * @category 	WoltNet Wiki
 */
class ArticleLocation implements IUserOnlineLocation {
	/**
	 * article ids
	 * @var array<integer>
	 */
	protected $articleIDs = array();

	/**
	 * list of articles
	 * @var array<wdb\data\article\Article>
	 */
	protected $projects = null;

	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::cache()
	 */
	public function cache(UserOnline $user) {
		if ($user->objectID) $this->articleIDs[] = $user->objectID;
	}

	/**
	 * @see wcf\system\user\online\location\IUserOnlineLocation::get()
	 */
	public function get(UserOnline $user, $languageVariable = '') {
		if ($this->articles === null) {
			$this->readArticles();
		}

		if (!isset($this->articles[$user->objectID])) {
			return '';
		}

		return WCF::getLanguage()->getDynamicVariable($languageVariable, array('article' => $this->articles[$user->objectID]));
	}

	/**
	 * Loads the articles.
	 */
	protected function readArticles() {
		$this->articles = array();

		if (empty($this->articleIDs)) return;
		$this->articleIDs = array_unique($this->articleIDs);
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs();
		if (empty($categoryIDs)) return;


		$articleList = new ArticleList();
		$articleList->getConditionBuilder()->add('article.articleID IN (?)', array($this->articleIDs));
		$articleList->getConditionBuilder()->add('article.categoryID IN (?)', array($categoryIDs));
		$articleList->getConditionBuilder()->add("article.isActive = '1'");
		$articleList->sqlLimit = 0;
		$articleList->readObjects();
		$this->articles = $articleList->getObjects();
	}
}
