<?php
namespace wiki\data\article\version;
use wcf\data\DatabaseObjectEditor;

/**
 * Represents a article label.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2013 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.version
 * @category	WoltNet Wiki
 */
class ArticleVersionEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wiki\data\article\version\ArticleVersion';
}
