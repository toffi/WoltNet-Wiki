<?php
namespace wiki\data\article;

use wcf\system\tagging\TagEngine;
use wcf\data\tag\Tag;
use wcf\system\WCF;

/**
 * Represents a list of tagged articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article
 * @category Woltnet - Wiki
 */
class TaggedArticleList extends ViewableArticleList {

    /**
     *
     * @see wcf\data\DatabaseObjectDecorator::$baseClass
     */
    protected static $baseClass = 'wiki\data\article\TaggedArticle';

    /**
     * Creates a new TaggedArticleList object.
     */
    public function __construct(Tag $tag) {
        parent::__construct();

        $this->getConditionBuilder()->add('tag_to_object.objectTypeID = ? AND tag_to_object.languageID = ? AND tag_to_object.tagID = ?', array (
                TagEngine::getInstance()->getObjectTypeID('com.woltnet.wiki.article'),
                $tag->languageID,
                $tag->tagID
        ));
        $this->getConditionBuilder()->add('article.articleID = tag_to_object.objectID');
    }

    /**
     *
     * @see wcf\data\DatabaseObjectList::countObjects()
     */
    public function countObjects() {
        $sql = "SELECT	COUNT(*) AS count
            FROM	wcf" . WCF_N . "_tag_to_object tag_to_object,
                wiki" . WCF_N . "_article article
            " . $this->sqlConditionJoins . "
            " . $this->getConditionBuilder();
        $statement = WCF::getDB()->prepareStatement($sql);
        $statement->execute($this->getConditionBuilder()->getParameters());
        $row = $statement->fetchArray();
        return $row['count'];
    }

    /**
     *
     * @see wcf\data\DatabaseObjectList::readObjectIDs()
     */
    public function readObjectIDs() {
        $this->objectIDs = array ();
        $sql = "SELECT	tag_to_object.objectID
            FROM	wcf" . WCF_N . "_tag_to_object tag_to_object,
                wiki" . WCF_N . "_article article
                " . $this->sqlConditionJoins . "
                " . $this->getConditionBuilder() . "
                " . (! empty($this->sqlOrderBy) ? "ORDER BY " . $this->sqlOrderBy : '');
        $statement = WCF::getDB()->prepareStatement($sql, $this->sqlLimit, $this->sqlOffset);
        $statement->execute($this->getConditionBuilder()->getParameters());
        while($row = $statement->fetchArray()) {
            $this->objectIDs[] = $row['objectID'];
        }
    }
}