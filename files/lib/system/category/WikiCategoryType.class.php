<?php
namespace wiki\system\category;

use wcf\system\category\AbstractCategoryType;

class WikiCategoryType extends AbstractCategoryType {
    /**
     * @see wcf\system\category\AbstractCategoryType::$permissionPrefix
     */
    protected $permissionPrefix = 'admin.wiki.category';

    /**
     * @see wcf\system\category\AbstractCategoryType::$langVarPrefix
     */
    protected $langVarPrefix = 'wiki.category';

    /**
     * @see wcf\system\category\AbstractCategoryType::$objectTypes
     */
    protected $objectTypes = array('com.woltlab.wcf.acl' => 'com.woltnet.wiki.category');

    /**
     * @see wcf\system\category\AbstractCategoryType::$forceDescription
     */
    protected $forceDescription = false;

    /**
    * @see  wcf\system\category\ICategoryType::getApplication()
    */
    public function getApplication() {
        return 'wiki';
    }
}
