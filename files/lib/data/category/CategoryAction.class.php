<?php
namespace wiki\data\category;

use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\database\util\PreparedStatementConditionBuilder;

/**
 * Manages the category cache.
 *
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.category
 * @category 	WoltNet - Wiki
 */
class CategoryAction extends AbstractDatabaseObjectAction {
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wiki\data\category\CategoryEditor';
	
	/**
	 * @see wcf\data\AbstractDatabaseObjectAction::create()
	 */
	public function create() {
		// remove position from data
		$position = null;
		if (isset($this->parameters['data']['position'])) {
			$position = $this->parameters['data']['position'];
			unset($this->parameters['data']['position']);
		}
	
		// create category
		$category = parent::create();
	
		// set position
		$this->setPosition($category, $this->parameters['data']['parentID'], $position);
	
		return $category;
	}
	
	/**
	 * Adds a category to a specific position in the category tree.
	 *
	 * @param	wiki\data\category\Category	$category
	 * @param	integer				$parentID
	 * @param	integer				$position
	 */
	protected function setPosition(Category $category, $parentID, $position = null) {
		if ($position !== null) {
			$sql = "UPDATE	wiki".WIKI_N."_category
			SET	position = position + 1
			WHERE	parentID = ?
				AND position >= ?";
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute(array(
					$parentID,
					$position
			));
		}

		$conditions = new PreparedStatementConditionBuilder();
		if ($parentID === null) {
			$conditions->add("parentID IS NULL");
		}
		else {
			$conditions->add("parentID = ?", array($parentID));
		}
		if ($position !== null) $conditions->add("position <= ?", array($position));
	
		$sql = "SELECT	MAX(position) AS position
			FROM	wiki".WIKI_N."_category
		".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		$row = $statement->fetchArray();
	
		if (!$row) $position = 1;
		else $position = $row['position'] + 1;
	
		// save position
		$categoryEditor = new CategoryEditor($category);
		$categoryEditor->update(array(
				'position' => $position
		));
	}
}
