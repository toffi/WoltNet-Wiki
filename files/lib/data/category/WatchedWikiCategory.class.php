<?php
namespace wiki\data\category;

/**
* Represents a watched category.
*
* @author	Rene Gessinger (NurPech)
* @copyright	2012 woltnet
* @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
* @package	com.woltnet.wiki
* @subpackage	data.project
* @category	WoltNet Wiki
*/
class WatchedWikiCategory extends WikiCategory {

	/**
	* Return template name
	*
	* @return string
	*/
	public function getTemplateName() {
		return 'watchedArticle';
	}
	
	public function getApplication() {
		return 'wiki';
	}
}
