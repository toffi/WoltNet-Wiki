<?php
namespace wiki\system\user\activity\event;

use wiki\data\article\ArticleList;
use wcf\system\user\activity\event\IUserActivityEvent;
use wcf\system\SingletonFactory;
use wcf\system\WCF;

/**
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.user.activity.event
 * @category WoltNet - Wiki
 */
class ArticleUserActivityEvent extends SingletonFactory implements IUserActivityEvent {

    /**
     *
     * @see wcf\system\user\activity\event\IUserActivityEvent::prepare()
     */
    public function prepare(array $events) {
        $objectIDs = array ();
        foreach($events as $event) {
            $objectIDs[] = $event->objectID;
        }

        // fetch articles
        $ArticleList = new ArticleList();
        $ArticleList->getConditionBuilder()->add("article.articleID IN (?)", array (
                $objectIDs
        ));
        $ArticleList->sqlLimit = 0;
        $ArticleList->readObjects();
        $articles = $ArticleList->getObjects();

        // set message
        foreach($events as $event) {
            if(isset($articles[$event->objectID])) {
                $article = $articles[$event->objectID];

                // validate permissions
                if(! $article->getActiveVersion()->canEnter()) {
                    continue;
                }
                $event->setIsAccessible();

                // short output
                $text = WCF::getLanguage()->getDynamicVariable('wcf.user.profile.recentActivity.article', array (
                        'article' => $article
                ));
                $event->setTitle($text);

                // set description
                $event->setDescription($articles[$event->objectID]->getExcerpt());
            } else {
                $event->setIsOrphaned();
            }
        }
    }
}
