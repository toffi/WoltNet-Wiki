<?php
namespace wiki\action;

use wiki\data\article\ArticleCache;
use wiki\data\article\ArticleAction;
use wcf\action\AbstractAction;
use wcf\util\HeaderUtil;
use wcf\system\request\LinkHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;

/**
 * This class deletes the given Article
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage action
 * @category WoltNet - Wiki
 */
class ArticleDeleteAction extends AbstractAction {

    public $articleID = 0;

    public $article = null;

    /**
     * database object action
     *
     * @var wcf\data\AbstractDatabaseObjectAction
     */
    public $objectAction = null;

    /**
     *
     * @see wiki\action\AbstractWikiAction::readParameters()
     */
    public function readParameters() {
        parent::readParameters();

        if(isset($_GET['id']))
            $this->articleID = intval($_GET['id']);

        $this->readData();
    }

    /**
     *
     * @see wiki\action\AbstractWikiAction::readData()
     */
    public function readData() {
        $this->article = ArticleCache::getInstance()->getArticle($this->articleID);

        if($this->article === null || ! $this->article->articleID) {
            throw new IllegalLinkException();
        }

        if(! $this->article->isDeletable()) {
            throw new PermissionDeniedException();
        }
    }

    /**
     *
     * @see wcf\action\AbstractAction::execute()
     */
    public function execute() {
        $this->objectAction = new ArticleAction(array (
                $this->article->articleID
        ), 'delete');
        $this->objectAction->validateAction();
        $this->objectAction->executeAction();

        HeaderUtil::redirect(LinkHandler::getInstance()->getLink('Category', array (
                'application' => 'wiki',
                'object' => $this->article->getCategory()
        )));
    }
}
