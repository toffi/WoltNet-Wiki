<?php
namespace wiki\system\dashboard\box;
use wiki\data\article\ViewableArticleList;
use wiki\data\category\WikiCategory;

use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\dashboard\box\AbstractDashboardBoxSidebar;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Dashboard sidebar box for updated article list.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.dashboard.box
 * @category 	WoltNet Wiki
 */
class UpdatedArticlesDashboardBox extends AbstractDashboardBoxSidebar {

	/**
	 * updated article list
	 * @var wiki\data\article\ViewableArticleList
	 */
	public $updatedArticleList = null;

	/**
	 * @see	wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);

		// get category id ids
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs(array('canViewCategory', 'canEnterCategory', 'canReadArticle'));
		if (!count($categoryIDs)) return;

		// read articles
		$this->updatedArticleList = new ViewableArticleList();
		$this->updatedArticleList->getConditionBuilder()->add('article.categoryID IN (?)', array($categoryIDs));
		$this->updatedArticleList->getConditionBuilder()->add('article.isActive = ?', array('1'));
		$this->updatedArticleList->getConditionBuilder()->add('article.versionID != ?', array('0'));
		$this->updatedArticleList->getConditionBuilder()->add('article.isDeleted = ?', array('0'));
		if (count(LanguageFactory::getInstance()->getContentLanguages())) {
			$this->updatedArticleList->getConditionBuilder()->add('(article.languageID IN (?) OR article.languageID IS NULL)', array(WCF::getUser()->getLanguageIDs()));
		}
		print_r(WCF::getUser()->getLanguageIDs());
		$this->updatedArticleList->sqlLimit = 5;
		$this->updatedArticleList->sqlOrderBy = 'article.time DESC';
		$this->updatedArticleList->readObjects();
	}

	/**
	 * @see	wcf\system\dashboard\box\AbstractDashboardBoxContent::render()
	 */
	protected function render() {
		WCF::getTPL()->assign(array(
			'updatedArticleList' => $this->updatedArticleList
		));

		return WCF::getTPL()->fetch('dashboardBoxUpdatedArticles', 'wiki');
	}
}
