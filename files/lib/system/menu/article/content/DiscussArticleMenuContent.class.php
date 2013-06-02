<?php
namespace wiki\system\menu\article\content;
use wiki\data\article\ArticleCache;

use wcf\system\comment\CommentHandler;
use wcf\system\event\EventHandler;
use wcf\system\menu\article\content\IArticleMenuContent;
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
class DiscussArticleMenuContent extends SingletonFactory implements IArticleMenuContent {

  public $articleID = 0;

  public $articleVersionID = 0;

  /**
   * comment list object
   * @var	wcf\data\comment\StructuredCommentList
   */
  public $commentList = null;

  /**
   * comment manager object
   * @var	wcf\system\comment\manager\ICommentManager
   */
  public $commentManager = null;

  /**
   * object type id
   * @var	integer
   */
  public $objectTypeID = 0;

  /**
   * @see	wcf\system\SingletonFactory::init()
   */
  protected function init() {
    EventHandler::getInstance()->fireAction($this, 'shouldInit');

    EventHandler::getInstance()->fireAction($this, 'didInit');
  }

  public function readData() {
    $this->objectTypeID = CommentHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.articleComment');
    $objectType = CommentHandler::getInstance()->getObjectType($this->objectTypeID);
    $this->commentManager = $objectType->getProcessor();

    $this->commentList = CommentHandler::getInstance()->getCommentList($this->commentManager, $this->objectTypeID, $this->articleID);
  }

  /**
   * @see	wiki\system\menu\article\content\IArticleMenuContent::getContent()
   */
  public function getContent($articleVersionID) {
    $this->articleVersionID = $articleVersionID;
    $articleVersion = ArticleCache::getInstance()->getArticleVersion($articleVersionID);

    $this->articleID = $articleVersion->articleID;
    $article = ArticleCache::getInstance()->getArticle($this->articleID);

    $this->readData();

    WCF::getTPL()->assign(array(
        'article'			=> $article,
        'commentList' 			=> $this->commentList,
        'commentObjectTypeID'		=> $this->objectTypeID,
        'commentCanAdd' 		=> $this->commentManager->canAdd($this->articleVersionID),
        'lastCommentTime' => $this->commentList->getMinCommentTime(),
        'commentsPerPage' 		=> $this->commentManager->getCommentsPerPage()
    ));
    return WCF::getTPL()->fetch('articleCommentList', 'wiki');
  }
}
