<?php
namespace wiki\data\category\suggestion;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\moderation\queue\ModerationQueueCategorySuggestionManager;

/**
 * @author	Rene Gessinger
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category.suggestion
 * @category 	WoltNet - Wiki
 */
class CategorySuggestionAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wiki\data\category\suggestion\CategorySuggestionEditor';
	
	/**
	 * @see DatabaseObjectEditor::create()
	 */
	public function create() {
		$object = call_user_func(array($this->className, 'create'), $this->parameters);
		
		ModerationQueueCategorySuggestionManager::getInstance()->addSuggestedContent('com.woltnet.wiki.moderation.category.suggestion', $object->suggestionID);
	
		return $object;
	}
}
