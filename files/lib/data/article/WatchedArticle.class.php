<?php
namespace wiki\data\article;

/**
* Represents a watched article.
*
* @author	Rene Gessinger (NurPech)
* @copyright	2012 woltnet
* @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
* @package	com.woltnet.wiki
* @subpackage	data.project
* @category	WoltNet Wiki
*/
class WatchedArticle extends ViewableArticle {

	/**
	* Return template name
	*
	* @return string
	*/
	public function getTemplateName() {
		return 'watchedArticle';
	}
}
