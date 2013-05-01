<?php
namespace wiki\system\event\listener;

/**
* Adds a new route to RouteHandler
*
* @author Rene Gessinger (NurPech)
* @copyright 2013 woltnet
* @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
* @package com.woltnet.wiki
* @subpackage system.event.listener
* @category WoltNet Wiki
*/
class RouteHandlerListener implements \wcf\system\event\IEventListener {
    /**
    * @see \wcf\system\event\IEventListener::execute()
    */
    public function execute($eventObj, $className, $eventName) {
        $route = new \wcf\system\request\Route('wikiArticle');
        $route->setSchema('/{controller}/{categoryName}/{id}/{versionID}');
        $route->setParameterOption('controller', null, 'Article');
        $route->setParameterOption('categoryName', null, null);
        $route->setParameterOption('action', null, null);
        $route->setParameterOption('id', null, '\d+');
        $route->setParameterOption('versionID', null, '\d+', true);
        $eventObj->addRoute($route);
    }
}