<?php
namespace wiki\data\category;

use wcf\data\category\ViewableCategoryNode;
use wcf\data\DatabaseObject;

/**
 * Represents a wiki category node.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category	WoltNet Wiki
 */
class WikiCategoryNode extends ViewableCategoryNode {	
	/**
	 * @see    wcf\data\category\CategoryNode::fulfillsConditions()
	 */
	protected function fulfillsConditions(DatabaseObject $category) {
		if (parent::fulfillsConditions($category)) {
			$category = new WikiCategory($category);
	
			return $category->isAccessible();
		}
	
		return false;
	}
	
	//TODO: implement this
	/**
	 * Returns count of articles
	 */
	public function getArticles() {
		return 0;
	}
}
