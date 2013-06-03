<?php
namespace wiki\data\article;

use wiki\system\cache\builder\ArticleVersionCacheBuilder;
use wiki\system\cache\builder\ArticleCacheBuilder;
use wiki\system\cache\builder\ArticlePermissionCacheBuilder;
use wcf\data\DatabaseObjectEditor;
use wcf\data\IEditableCachedObject;

/**
 *
 * @author Jean-Marc Licht
 * @copyright 2012 WoltNet
 * @license GNU Lesser General Public License
 *          <http://opensource.org/licenses/lgpl-license.php>
 * @package com.woltnet.wiki
 * @subpackage data.article
 * @category WoltNet - Wiki
 */
class ArticleEditor extends DatabaseObjectEditor implements IEditableCachedObject {
	/**
	 *
	 * @see wcf\data\DatabaseObjectDecorator::$baseClass
	 */
	protected static $baseClass = 'wiki\data\article\Article';
	
	/**
	 *
	 * @see wcf\data\IEditableCachedObject::resetCache()
	 */
	public static function resetCache() {
		ArticleCacheBuilder::getInstance()->reset();
		ArticleVersionCacheBuilder::getInstance()->reset();
		ArticlePermissionCacheBuilder::getInstance()->reset();
	}
}
