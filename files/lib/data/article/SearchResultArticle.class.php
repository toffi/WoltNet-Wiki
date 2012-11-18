<?php
namespace wiki\data\article;
use wcf\system\bbcode\KeywordHighlighter;
use wcf\util\StringUtil;

/**
 * Represents a list of search result.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	Woltnet - Wiki
 */
class SearchResultArticle extends ViewableArticle {

	/**
	 * Gets a highlighted topic.
	 *
	 * @return string
	 */
	public function getHighlightedTopic() {
		return KeywordHighlighter::getInstance()->doHighlight(StringUtil::encodeHTML($this->subject));
	}
}
