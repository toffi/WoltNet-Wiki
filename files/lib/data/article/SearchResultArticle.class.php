<?php
namespace wiki\data\article;
use wcf\data\search\ISearchResultObject;
use wcf\system\request\LinkHandler;
use wcf\system\search\SearchResultTextParser;

/**
 * Represents a list of search result.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	Woltnet - Wiki
 */
class SearchResultArticle extends ViewableArticle implements ISearchResultObject {
  /**
   * article object
   * @var wiki\data\article\Article
   */
  public $article = null;

  /**
   * Returns the article object.
   *
   * @return wiki\data\article\Article
   */
  public function getArticle() {
    if ($this->article === null) {
      $this->article = new Article($this->articleID);
    }

    return $this->article;
  }

  /**
  * @see wiki\data\article\Article::getFormattedMessage()
  */
  public function getFormattedMessage() {
    return SearchResultTextParser::getInstance()->parse($this->getDecoratedObject()->getFormattedMessage());
  }

  /**
  * @see wcf\data\search\ISearchResultObject::getSubject()
  */
  public function getSubject() {
    return $this->subject;
  }

  /**
  * @see wcf\data\search\ISearchResultObject::getLink()
  */
  public function getLink($query = '') {
    if ($query) {
      return LinkHandler::getInstance()->getLink('Article', array(
        'application' => 'wiki',
        'object' => $this->getArticle(),
        'highlight' => urlencode($query)
      ));
    }

    return $this->getDecoratedObject()->getLink();
  }

  /**
   * @see wcf\data\search\ISearchResultObject::getTime()
   */
  public function getTime() {
    return $this->time;
  }

  /**
   * @see wcf\data\search\ISearchResultObject::getObjectTypeName()
   */
  public function getObjectTypeName() {
    return 'com.woltnet.wiki.article';
  }

  /**
   * @see wcf\data\search\ISearchResultObject::getContainerTitle()
   */
  public function getContainerTitle() {
    return $this->getArticle()->getCategory()->getTitle();
  }

  /**
   * @see wcf\data\search\ISearchResultObject::getContainerLink()
   */
  public function getContainerLink() {
    return LinkHandler::getInstance()->getLink('Category',  array(
        'application' => 'wiki',
        'object' => $this->getArticle()->getCategory()
      ));
  }

  public function getUserProfile() {
  	return $this->getArticle()->getAuthor();
  }
}
