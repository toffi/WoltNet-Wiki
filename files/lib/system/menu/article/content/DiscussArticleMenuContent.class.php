<?php
namespace wiki\system\menu\article\content;
use wiki\data\article\ArticleCache;

use wcf\system\comment\CommentHandler;
use wcf\system\cache\CacheHandler;
use wcf\system\event\EventHandler;
use wcf\system\menu\article\content\IArticleMenuContent;
use wcf\system\user\activity\event\UserActivityEventHandler;
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

		$this->commentList = CommentHandler::getInstance()->getCommentList($this->objectTypeID, $this->commentManager, $this->articleID);
	}

	/**
	 * @see	wiki\system\menu\article\content\IArticleMenuContent::getContent()
	 */
	public function getContent($articleID) {
		$this->articleID = $articleID;

		$this->readData();

		$article = ArticleCache::getInstance()->getArticle($articleID);
		WCF::getTPL()->assign(array(
				'article'			=> $article,
				'commentList' 			=> $this->commentList,
				'commentObjectTypeID'		=> $this->objectTypeID,
				'commentCanAdd' 		=> $this->commentManager->canAdd($this->articleID),
				'commentsPerPage' 		=> $this->commentManager->getCommentsPerPage()
		));
		return WCF::getTPL()->fetch('articleCommentList', 'wiki');
	}
}
