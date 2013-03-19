<?php
namespace wiki\action;

use wiki\data\article\ArticleEditor;
use wiki\data\article\Article;
use wiki\data\article\ArticleAction;
use wiki\data\article\ArticleCache;

use wcf\action\AbstractAction;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\moderation\queue\ModerationQueueActivationManager;

/**
 * This class activates the given Article
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	action
 * @category 	WoltNet - Wiki
 */
class ArticleActivateAction extends AbstractAction {
	public $articleID = 0;

	public $article = null;

	/**
	 * needed permissions to execute this action
	 * @see \wcf\action\AbstractAction
	 */
	public $neededPermissions = array('mod.wiki.article.canActivateArticle');

	/**
	 * @see wiki\action\AbstractWikiAction::readParameters()
	 */
	public function readParameters() {
		parent::readParameters();

		if(isset($_GET['id'])) $this->articleID = intval($_GET['id']);

		$this->readData();
	}

	/**
	 * @see wiki\action\AbstractWikiAction::readData()
	 */
	public function readData() {
		//parent::readData();

		$this->article = ArticleCache::getInstance()->getArticle($this->articleID);

		if(!$this->article->articleID) {
			throw new IllegalLinkException();
		}
	}

	/**
	 * @see wcf\action\AbstractAction::execute()
	 */
	public function execute() {
		parent::execute();
		$editor = $this->article->getEditor();
		$editor->setActive();
		ArticleEditor::resetCache();

		$moderationManager = ModerationQueueActivationManager::getInstance()->removeModeratedContent('com.woltnet.wiki.article', array($this->articleID));
		HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Article', array(
				'application' => 'wiki',
				'object' => $this->article
		)));
	}
}
