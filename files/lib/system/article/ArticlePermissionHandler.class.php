<?php
namespace wiki\system\article;
use wiki\data\article\Article;
use wiki\data\article\ArticleCache;

use wcf\system\acl\ACLHandler;
use wcf\system\cache\CacheHandler;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\user\storage\UserStorageHandler;
use wcf\system\SingletonFactory;
use wcf\system\WCF;
use wcf\util\StringUtil;

/**
 * get article permissions
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @package	com.woltnet.wiki
 * @subpackage	system.article
 * @category 	WoltNet Wiki
 */
class ArticlePermissionHandler extends SingletonFactory {

	/**
	 * list of permissions
	 * @var array
	 */
	protected $articlePermissions = array();

	/**
	 * @see wcf\system\SingletonFactory::init()
	 */
	protected function init() {
		// get groups permissions
		$groups = implode(',', WCF::getUser()->getGroupIDs());
		$groupsFileName = StringUtil::getHash(implode('-', WCF::getUser()->getGroupIDs()));
		CacheHandler::getInstance()->addResource('articlePermission-'.$groups, WIKI_DIR.'cache/cache.articlePermission-'.$groupsFileName.'.php', 'wiki\system\cache\builder\ArticlePermissionCacheBuilder');
		$this->articlePermissions = CacheHandler::getInstance()->get('articlePermission-'.$groups);

		// get user permissions
		if (WCF::getUser()->userID) {
			// get data from storage
			UserStorageHandler::getInstance()->loadStorage(array(WCF::getUser()->userID));

			// get ids
			$data = UserStorageHandler::getInstance()->getStorage(array(WCF::getUser()->userID), 'articleUserPermissions');

			// cache does not exist or is outdated
			if ($data[WCF::getUser()->userID] === null) {
				$userPermissions = array();

				$conditionBuilder = new PreparedStatementConditionBuilder();
				$conditionBuilder->add('acl_option.packageID IN (?)', array(PACKAGE_ID));
				$conditionBuilder->add('acl_option.objectTypeID = ?', array(ACLHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article')));
				$conditionBuilder->add('option_to_user.optionID = acl_option.optionID');
				$conditionBuilder->add('option_to_user.userID = ?', array(WCF::getUser()->userID));
				$sql = "SELECT		option_to_user.objectID AS articleID, option_to_user.optionValue,
							acl_option.optionName AS permission
					FROM		wcf".WCF_N."_acl_option acl_option,
							wcf".WCF_N."_acl_option_to_user option_to_user
							".$conditionBuilder;
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute($conditionBuilder->getParameters());
				while ($row = $statement->fetchArray()) {
					$userPermissions[$row['articleID']][$row['permission']] = $row['optionValue'];
				}

				// update storage data
				UserStorageHandler::getInstance()->update(WCF::getUser()->userID, 'articleUserPermissions', serialize($userPermissions));
			}
			else {
				$userPermissions = unserialize($data[WCF::getUser()->userID]);
			}

			foreach ($userPermissions as $articleID => $permissions) {
				foreach ($permissions as $name => $value) {
					$this->articlePermissions[$articleID][$name] = $value;
				}
			}
		}
	}

	/**
	 * Gets a specific article permission.
	 *
	 * @param	integer		$articleID
	 * @param	string		$permission
	 * @return	boolean
	 */
	public function getPermission($articleID, $permission) {
		if (isset($this->articlePermissions[$articleID][$permission])) return $this->articlePermissions[$articleID][$permission];

		$categoryID = ArticleCache::getInstance()->getArticle($articleID)->categoryID;
		//if (CategoryPermissionHandler::getInstance()->getPermission($categoryID, $permission)) return CategoryPermissionHandler::getInstance()->getPermission($categoryID, $permission);

		return WCF::getSession()->getPermission('user.wiki.article.read.'.$permission) || WCF::getSession()->getPermission('user.wiki.article.write.'.$permission);
	}

	/**
	 * Gets a specific moderator permission.
	 *
	 * @param	string		$permission
	 * @return	boolean
	 */
	public function getModeratorPermission($articleID, $permission) {
		$categoryID = ArticleCache::getInstance()->getArticle($articleID)->categoryID;
		//if (CategoryPermissionHandler::getInstance()->getModeratorPermission($categoryID, $permission)) return CategoryPermissionHandler::getInstance()->getPermission($categoryID, $permission);

		return WCF::getSession()->getPermission('mod.wiki.article.'.$permission);
	}

	/**
	 * Resets the category permission cache.
	 */
	public function resetCache() {
		CacheHandler::getInstance()->clear(WIKI_DIR.'cache/', 'cache.articlePermission-');

	}
}
