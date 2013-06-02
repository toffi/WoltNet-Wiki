<?php
namespace wiki\page;
use wiki\data\article\ArticleCache;

use wcf\system\WCF;
use wcf\system\user\collapsible\content\UserCollapsibleContentHandler;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\request\LinkHandler;
use wcf\system\language\LanguageFactory;
use wcf\system\menu\article\ArticleMenu;
use wcf\system\message\quote\MessageQuoteManager;
use wcf\util\HeaderUtil;
use wcf\system\exception\IllegalLinkException;
use wcf\page\AbstractPage;
use wcf\system\tagging\TagEngine;
use wcf\system\visitTracker\VisitTracker;

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

  public $versionID = 0;

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
    if(isset($_GET['versionID'])) $this->versionID = intval($_GET['versionID']);
    if(isset($_GET['languageID'])) $this->languageID = intval($_GET['languageID']);
  }

  /**
   * @see wcf\page\AbstractPage::readData()
   */
  public function readData() {
    parent::readData();

    if($this->versionID > 0) {
        $this->article = ArticleCache::getInstance()->getArticleVersion($this->versionID);
    } else {
        $this->article = ArticleCache::getInstance()->getArticle($this->articleID)->getActiveVersion();
    }



    if(!$this->article->articleID || (!$this->article->canEnter())) {
      throw new IllegalLinkException();
    }

    $this->availableContentLanguages = LanguageFactory::getInstance()->getContentLanguages();

    if(count($this->availableContentLanguages) > 0 && $this->languageID > 0 && $this->article->languageID != $this->languageID) HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array('application' => 'wiki', 'object' => $this->article->getArticleToLanguage($this->languageID), 'l' => $this->languageID)));

    if(!$this->article->isActive) {
      $this->showNotActive = true;
    }

    foreach($this->article->getArticle()->getCategory()->getParentCategories() AS $categoryItem) {
      WCF::getBreadcrumbs()->add(new Breadcrumb($categoryItem->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'wiki',
          'object' => $categoryItem
      ))));
    }
    WCF::getBreadcrumbs()->add(new Breadcrumb($this->article->getArticle()->getCategory()->getTitle(), LinkHandler::getInstance()->getLink('Category', array(
          'application' => 'wiki',
          'object' => $this->article->getArticle()->getCategory()
    ))));

    $activeMenuItem = ArticleMenu::getInstance()->getActiveMenuItem();
    $contentManager = $activeMenuItem->getContentManager();
    $this->articleContent = $contentManager->getContent($this->article->versionID);

    VisitTracker::getInstance()->trackObjectVisit('com.woltnet.wiki.article', $this->article->articleID);
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
      'commentCount'					=> count($this->article->getArticle()->getCommentList()),
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
