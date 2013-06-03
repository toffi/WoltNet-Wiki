<?php
namespace wiki\system\user\object\watch;

use wiki\data\category\WikiCategory;
use wcf\data\object\type\AbstractObjectTypeProcessor;
use wcf\data\category\Category;
use wcf\system\user\object\watch\IUserObjectWatch;

/**
 * User Watch object for categories.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @license GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage system.user.object.watch
 * @category WoltNet Wiki
 */
class CategoryUserObjectWatch extends AbstractObjectTypeProcessor implements IUserObjectWatch {

    /**
     *
     * @see wcf\system\user\object\watch\IUserObjectWatch::getUnreadCount()
     */
    public function getUnreadCount($userID) {
    }

    /**
     *
     * @see wcf\system\user\object\watch\IUserObjectWatch::getUnreadObjects()
     */
    public function getUnreadObjects($userID, $limit = 5) {
    }

    /**
     *
     * @see wcf\system\user\object\watch\IUserObjectWatch::getObjectIDs()
     */
    public function getObjectIDs($userID) {
    }

    /**
     *
     * @see wcf\system\user\object\watch\IUserObjectWatch::getObjects()
     */
    public function getObjects(array $objectIDs) {
    }

    /**
     *
     * @see \wcf\system\user\object\watch\IUserObjectWatch::resetUserStorage()
     */
    public function resetUserStorage(array $userIDs) {
    }

    /**
     *
     * @see wcf\system\user\object\watch\IUserObjectWatch::validateObjectID()
     */
    public function validateObjectID($objectID) {
        // get category
        $category = new Category($objectID);
        if(! $category->categoryID)
            return false;
        $category = new WikiCategory($category);

        // check permission
        return $category->checkPermission();
    }
}
