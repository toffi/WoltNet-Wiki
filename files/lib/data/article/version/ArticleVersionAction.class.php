<?php
namespace wiki\data\article\version;
use wiki\util\KeywordUtil;

use wcf\system\tagging\TagEngine;
use wcf\system\search\SearchIndexManager;
use wcf\data\AbstractDatabaseObjectAction;


/**
 * Represents a article version.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2013 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.version
 * @category	WoltNet Wiki
 */
class ArticleVersionAction extends AbstractDatabaseObjectAction {
    /**
     * @see	wcf\data\AbstractDatabaseObjectAction::$className
     */
    protected $className = 'wiki\data\article\version\ArticleVersionEditor';

    /**
     * @see DatabaseObjectEditor::create()
     */
    public function create() {
        $object = call_user_func(array($this->className, 'create'), $this->parameters);

        // update search index
        SearchIndexManager::getInstance()->add('com.woltnet.wiki.article', $object->articleID, $object->message, $object->subject, $object->time, $object->userID, $object->username, $object->languageID);

        TagEngine::getInstance()->addObjectTags('com.woltnet.wiki.article', $object->articleID, KeywordUtil::getKeywords($object->message), $object->getArticle()->languageID);

        return $object;
    }
}
