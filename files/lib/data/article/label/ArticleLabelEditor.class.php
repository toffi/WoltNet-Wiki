<?php
namespace wiki\data\article\label;
use wcf\data\DatabaseObjectEditor;

/**
 * Represents a article label.
 * 
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.conversation.label
 * @category	WoltNet Wiki
 */
class ArticleLabelEditor extends DatabaseObjectEditor {
	/**
	 * @see	wcf\data\DatabaseObjectEditor::$baseClass
	 */
	protected static $baseClass = 'wiki\data\article\label\ArticleLabel';
}
