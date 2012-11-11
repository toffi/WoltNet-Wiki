<?php
namespace wiki\system;
use wcf\system\cache\CacheHandler;
use wcf\system\menu\page\PageMenu;
use wcf\system\application\AbstractApplication;
use wcf\system\breadcrumb\Breadcrumb;
use wcf\system\package\PackageDependencyHandler;
use wcf\system\request\LinkHandler;
use wcf\system\WCF;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system
 * @category 	WoltNet - Wiki
 */
class WIKICore extends AbstractApplication {
	protected $packageID = 0;

	public function __run() {
		$this->packageID = PackageDependencyHandler::getInstance()->getPackageID('com.woltnet.wiki');

		PageMenu::getInstance()->setActiveMenuItem('wiki.pageMenu.index');
		WCF::getBreadcrumbs()->add(new Breadcrumb(WCF::getLanguage()->get('wiki.breadCrumbs.index'), LinkHandler::getInstance()->getLink('Index', array('application' => 'wiki'))));
	}
}
