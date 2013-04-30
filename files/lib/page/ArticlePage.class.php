<?php
namespace wiki\page;
use wcf\system\tagging\TagEngine;

use wiki\data\article\ArticleAction;
use wiki\data\article\ViewableArticle;

use wcf\system\WCF;
use wiki\data\article\ArticleCache;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\language\LanguageFactory;
use wcf\system\menu\article\ArticleMenu;
use wcf\system\message\quote\MessageQuoteManager;
use wcf\util\HeaderUtil;
use wcf\system\exception\IllegalLinkException;
use wcf\page\AbstractPage;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	page
 * @category 	WoltNet - Wiki
 */
class ArticlePage extends AbstractPage {
  /**
   * @see wcf\page\AbstractPage::$enableTracking
   */
  public $enableTracking = true;

  /**
   * @see wcf\page\AbstractPage::$templateName
   */
  public $templatename = 'article';

  public $cache = null;

  public $article = null;

  public $articleID = 0;

  public $showNotActive = false;

  public $languageID = 0;

  /**
   * article content for active menu item
   * @var	string
   */
  public $articleContent = '';

  /**
   * List of all available content languages
   * @var array
   */
  public $availableContentLanguages = array();

  /**
   * @see wcf\page\AbstractPage::readParameters()
   */
  public function readParameters() {
    parent::readParameters();

    if(isset($_GET['id'])) $this->articleID = intval($_GET['id']);
    if(isset($_GET['l'])) $this->languageID = intval($_GET['l']);
  }

  /**
   * @see wcf\page\AbstractPage::readData()
   */
  public function readData() {
    parent::readData();

    $this->article = new ViewableArticle(ArticleCache::getInstance()->getArticle($this->articleID)->getActiveVersion());

    if(!$this->article->articleID || (!$this->article->canEnter())) {
      throw new IllegalLinkException();
    }

    $this->availableContentLanguages = LanguageFactory::getInstance()->getContentLanguages();

    if(count($this->availableContentLanguages) > 0 && $this->languageID > 0 && $this->article->languageID != $this->languageID) HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array('application' => 'wiki', 'object' => $this->article->getArticleToLanguage($this->languageID), 'l' => $this->languageID)));

    // update article visit
    if ($this->article->time > $this->article->visitTime) {
      $articleAction = new ArticleAction(array($this->article->getDecoratedObject()), 'markAsRead', array('visitTime' => $this->article->time));
      $articleAction->executeAction();
    }

    if(!$this->article->isActive) {
      $this->showNotActive = true;
    }

    foreach($this->article->getCategory()->getParentCategories() AS $categoryItem) {
      WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'wiki',
          'object' => $categoryItem
      ))));
    }
    WCF::getBreadcrumbs()->add(new Breadcrumb($this->article->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'wiki',
          'object' => $this->article->getCategory()
    ))));

    $activeMenuItem = ArticleMenu::getInstance()->getActiveMenuItem();
    $contentManager = $activeMenuItem->getContentManager();
    $this->articleContent = $contentManager->getContent($this->article->articleID);
  }

  /**
   * @see wcf\page\AbstractPage::assignVariables()
   */
  public function assignVariables() {
    parent::assignVariables();

    MessageQuoteManager::getInstance()->assignVariables();

    WCF::getTPL()->assign(array(
      'articleOverview'					=> $this->article,
      'showNotActive'					=> $this->showNotActive,
      'articleContent' 					=> $this->articleContent,
      'sidebarCollapsed' 				=> UserCollapsibleContentHandler::getInstance()->isCollapsed('com.woltlab.wcf.collapsibleSidebar', 'com.woltnet.wiki.article'),
      'sidebarName' 					=> 'com.woltnet.wiki.article',
      'commentCount'					=> count($this->article->getCommentList()),
      'availableContentLanguagesCount'	=> count($this->availableContentLanguages),
      'contentLanguages'				=> WCF::getUser()->getLanguageIDs(),
      'tags'							=> TagEngine::getInstance()->getObjectTags('com.woltnet.wiki.article', $this->article->articleID, array($this->article->languageID))
    ));
  }

  /**
   * @see wcf\page\ITrackablePage::getObjectType()
   */
  public function getObjectType() {
    return 'com.woltlab.wiki.article';
  }

  /**
   * @see wcf\page\ITrackablePage::getObjectID()
   */
  public function getObjectID() {
    return $this->articleID;
  }
}
