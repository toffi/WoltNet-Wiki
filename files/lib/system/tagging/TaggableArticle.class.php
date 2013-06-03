<?php
namespace wiki\system\tagging;

use wiki\data\article\TaggedArticleList;
use wcf\system\tagging\ITaggable;
use wcf\data\tag\Tag;

/**
 * Provides object type id for projects.
 *
 * @author Jean-Marc Licht
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.tagging
 * @category WoltNet Wiki
 */
class TaggableArticle implements ITaggable {

    /**
     *
     * @see wcf\system\tagging\ITaggable::getObjectTypeID()
     */
    public function getObjectList(Tag $tag) {
        return new TaggedArticleList($tag);
    }

    /**
     *
     * @see wcf\system\tagging\ITaggable::getApplication()
     */
    public function getApplication() {
        return 'wiki';
    }

    public function getTemplateName() {
        return 'taggedArticles';
    }
}
