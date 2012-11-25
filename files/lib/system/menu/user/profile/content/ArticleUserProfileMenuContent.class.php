<?php
namespace wiki\system\menu\user\profile\content;
use wiki\data\article\ViewableArticleList;
use wiki\data\category\WikiCategory;

use wcf\system\menu\user\profile\content\IUserProfileMenuContent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 * Handles user articles.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.menu.user.profile.content
 * @category 	WoltNet Wiki
 */
class ArticleUserProfileMenuContent extends SingletonFactory implements IUserProfileMenuContent {
	/**
	 * @see	wcf\system\menu\user\profile\content\IUserProfileMenuContent::getContent()
	 */
	public function getContent($userID) {
		// get accessible categoryIDs
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs(array('canViewCategory', 'canEnterCategory', 'canReadArticle'));

		$userArticleList = array();
		if(count($categoryIDs) > 0) {
		// get user articles
			$userArticleList = new ViewableArticleList();
			$userArticleList->getConditionBuilder()->add("article.categoryID IN (?)", array($categoryIDs));
			$userArticleList->getConditionBuilder()->add("article.userID = ?", array($userID));
			$userArticleList->getConditionBuilder()->add("article.isActive = '1'");
			$userArticleList->sqlLimit = 0;
			$userArticleList->readObjects();
			$userArticleList = $userArticleList->getObjects();
		}

		WCF::getTPL()->assign(array(
			'userArticleList' => $userArticleList,
			'userID' => $userID
		));

		return WCF::getTPL()->fetch('userProfileArticles');
	}
}
