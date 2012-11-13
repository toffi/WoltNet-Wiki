<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategory;

/**
 * Represents a wiki category.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategory extends ViewableCategory {
	/**
	 * Returns true if the category is accessible for the given user. If no
	 * user is given, the active user is used.
	 *
	 * @param    wcf\data\user\User        $user
	 * @return    boolean
	 */
	public function isAccessible() {
		return true;
	}
}
