<?php
namespace wiki\system\moderation\queue\category\suggestion;

use wcf\data\moderation\queue\ViewableModerationQueue;
use wcf\system\moderation\queue\IModerationQueueHandler;

/**
 * Default interface for moderation queue category suggestion handlers.
 * 
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.moderation.queue.category.suggestion
 * @category	WoltNet Wiki
 */
interface IModerationQueueCategorySuggestionHandler extends IModerationQueueHandler {
	/**
	 * Returns true, if current user can report given content.
	 * 
	 * @param	integer		$objectID
	 * @return	boolean
	 */
	public function canSuggest($objectID);
	
	/**
	 * Returns rendered template for reported content.
	 * 
	 * @param	wcf\data\moderation\queue\ViewableModerationQueue	$queue
	 * @return	string
	 */
	public function getSuggestedContent(ViewableModerationQueue $queue);
	
	/**
	 * Returns reported object.
	 * 
	 * @param	integer		$objectID
	 * @return	wcf\data\IUserContent
	 */
	public function getSuggestedObject($objectID);
}
