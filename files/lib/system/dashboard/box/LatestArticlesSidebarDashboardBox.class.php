<?php
namespace wiki\system\dashboard\box;

use wiki\data\article\ViewableArticleList;
use wiki\data\category\WikiCategory;
use wcf\data\dashboard\box\DashboardBox;
use wcf\page\IPage;
use wcf\system\dashboard\box\AbstractSidebarDashboardBox;
use wcf\system\language\LanguageFactory;
use wcf\system\WCF;

/**
 * Dashboard sidebar box for latest articles list.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.dashboard.box
 * @category WoltNet Wiki
 */
class LatestArticlesSidebarDashboardBox extends AbstractSidebarDashboardBox {

	/**
	 * latest article list
	 *
	 * @var wiki\data\project\ViewableArticleList
	 */
	public $latestArticleList = null;

	/**
	 *
	 * @see wcf\system\dashboard\box\IDashboardBox::init()
	 */
	public function init(DashboardBox $box, IPage $page) {
		parent::init($box, $page);
		
		// get category id ids
		$categoryIDs = WikiCategory::getAccessibleCategoryIDs(array (
				'canViewCategory',
				'canEnterCategory',
				'canReadArticle' 
		));
		if(! count($categoryIDs))
			return;
			
			// read articles
		$this->latestArticleList = new ViewableArticleList();
		$this->latestArticleList->getConditionBuilder()->add('article.categoryID IN (?)', array (
				$categoryIDs 
		));
		$this->latestArticleList->getConditionBuilder()->add('article_version.isActive = ?', array (
				'1' 
		));
		$this->latestArticleList->getConditionBuilder()->add('article_version.isDeleted = ?', array (
				'0' 
		));
		$this->latestArticleList->getConditionBuilder()->add('article.activeVersionID != ?', array (
				'null' 
		));
		if(count(LanguageFactory::getInstance()->getContentLanguages())) {
			$this->latestArticleList->getConditionBuilder()->add('(article.languageID IN (?))', array (
					WCF::getUser()->getLanguageIDs() 
			));
		}
		$this->latestArticleList->sqlConditionJoins = 'LEFT JOIN wiki' . WCF_N . '_article_version AS article_version ON (article_version.versionID = article.activeVersionID)';
		$this->latestArticleList->sqlLimit = 5;
		$this->latestArticleList->sqlOrderBy = 'article.articleID DESC';
		$this->latestArticleList->readObjects();
	}

	/**
	 *
	 * @see wcf\system\dashboard\box\AbstractDashboardBoxContent::render()
	 */
	protected function render() {
		WCF::getTPL()->assign(array (
				'latestArticleList' => $this->latestArticleList 
		));
		
		return WCF::getTPL()->fetch('dashboardBoxLatestArticlesSidebar', 'wiki');
	}
}
