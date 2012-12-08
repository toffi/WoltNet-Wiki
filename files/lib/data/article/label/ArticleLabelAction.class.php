<?php
namespace wiki\data\article\label;

use wcf\data\IClipboardAction;
use wcf\data\AbstractDatabaseObjectAction;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\clipboard\ClipboardHandler;
use wcf\system\exception\IllegalLinkException;
use wcf\system\exception\PermissionDeniedException;
use wcf\system\exception\UserInputException;
use wcf\system\WCF;
use wcf\util\ArrayUtil;
use wcf\util\StringUtil;

/**
 * Represents a article label.
 * 
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article.label
 * @category	WoltNet Wiki
 */
class ArticleLabelAction extends AbstractDatabaseObjectAction implements IClipboardAction {
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$className
	 */
	protected $className = 'wiki\data\article\label\ArticleLabelEditor';
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$permissionsUpdate
	 */
	protected $permissionsCreate = array('mod.wiki.category.canManageLabels');
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$permissionsDelete
	 */
	protected $permissionsDelete = array('mod.wiki.category.canManageLabels');
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::$permissionsUpdate
	 */
	protected $permissionsUpdate = array('mod.wiki.category.canManageLabels');
	
	/**
	 * article object
	 * 
	 * @var	wiki\data\article\Article
	 */
	public $article = null;
	
	/**
	 * article label list object
	 * 
	 * @var	wiki\data\article\label\ArticleLabelList
	 */
	public $labelList = null;
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::validateUpdate()
	 */
	public function validateUpdate() {
		parent::validateUpdate();
		
		if (count($this->objects) != 1) {
			throw new UserInputException('objectID');
		}
	}
	
	/**
	 * @see	wcf\data\AbstractDatabaseObjectAction::validateDelete()
	 */
	public function validateDelete() {
		parent::validateDelete();
		
		if (count($this->objects) != 1) {
			throw new UserInputException('objectID');
		}
	}
	
	/**
	 * Validates parameters to add a new label.
	 */
	public function validateAdd() {
		if (!WCF::getSession()->getPermission('mod.wiki.category.canManageLabels')) {
			throw new PermissionDeniedException();
		}
		
		$this->parameters['data']['labelName'] = (isset($this->parameters['data']['labelName'])) ? StringUtil::trim($this->parameters['data']['labelName']) : '';
		if (empty($this->parameters['data']['labelName'])) {
			throw new UserInputException('labelName');
		}
		
		$this->parameters['data']['cssClassName'] = (isset($this->parameters['data']['cssClassName'])) ? StringUtil::trim($this->parameters['data']['cssClassName']) : '';
		if (empty($this->parameters['data']['cssClassName']) || !in_array($this->parameters['data']['cssClassName'], ArticleLabel::getLabelCssClassNames())) {
			throw new UserInputException('cssClassName');
		}
		
		$this->parameters['data']['categoryID'] = (isset($this->parameters['data']['categoryID'])) ? intval($this->parameters['data']['categoryID']) : 0;
		if (empty($this->parameters['data']['categoryID'])) {
			throw new UserInputException('categoryID');
		}
		
		// 'none' is a pseudo value
		if ($this->parameters['data']['cssClassName'] == 'none') $this->parameters['data']['cssClassName'] = '';
	}
	
	/**
	 * Adds a new user-specific label.
	 * 
	 * @return	array
	 */
	public function add() {
		$label = ArticleLabelEditor::create(array(
			'categoryID' => $this->parameters['data']['categoryID'],
			'label' => $this->parameters['data']['labelName'],
			'cssClassName' => $this->parameters['data']['cssClassName']
		));
		
		return array(
			'actionName' => 'add',
			'cssClassName' => $label->cssClassName,
			'categoryID' => $label->categoryID,
			'label' => $label->label,
			'labelID' => $label->labelID
		);
	}
	
	/**
	 * Validates parameters for label assignment form.
	 */
	public function validateGetLabelForm() {
		// validate article id
		$this->parameters['articleIDs'] = (isset($this->parameters['articleIDs'])) ? ArrayUtil::toIntegerArray($this->parameters['articleIDs']) : array();
		if (empty($this->parameters['articleIDs'])) {
			throw new UserInputException('articleID');
		}
		
		// validate available labels
		$this->labelList = ArticleLabel::getLabelsByCategory($this->parameters['categoryID']);
		if (!count($this->labelList)) {
			throw new IllegalLinkException();
		}
	}
	
	/**
	 * Returns the label assignment form.
	 * 
	 * @return	array
	 */
	public function getLabelForm() {
		// read assigned labels
		$labelIDs = array();
		foreach ($this->labelList as $label) {
			$labelIDs[] = $label->labelID;
		}
		
		$assignedLabels = array();
		// read assigned labels if editing single article
		if (count($this->parameters['articleIDs']) == 1) {
			$articleID = current($this->parameters['articleIDs']);
			
			$conditions = new PreparedStatementConditionBuilder();
			$conditions->add("articleID = ?", array($articleID));
			$conditions->add("labelID IN (?)", array($labelIDs));
			
			$sql = "SELECT	labelID
				FROM	wiki".WCF_N."_article_label_to_object
				".$conditions;
			$statement = WCF::getDB()->prepareStatement($sql);
			$statement->execute($conditions->getParameters());
			$assignedLabels = array();
			while ($row = $statement->fetchArray()) {
				$assignedLabels[] = $row['labelID'];
			}
		}
		
		WCF::getTPL()->assign(array(
			'assignedLabels' => $assignedLabels,
			'article' => $this->article,
			'labelList' => $this->labelList
		));
		
		return array(
			'actionName' => 'getLabelForm',
			'template' => WCF::getTPL()->fetch('articleLabelAssignment', 'wiki')
		);
	}
	
	/**
	 * Validates parameters to assign labels for a article.
	 */
	public function validateAssignLabel() {
		$this->validateGetLabelForm();
		
		// validate given labels
		$this->parameters['labelIDs'] = (isset($this->parameters['labelIDs']) && is_array($this->parameters['labelIDs'])) ? ArrayUtil::toIntegerArray($this->parameters['labelIDs']) : array();
		if (!empty($this->parameters['labelIDs'])) {
			foreach ($this->parameters['labelIDs'] as $labelID) {
				$isValid = false;
				
				foreach ($this->labelList as $label) {
					if ($labelID == $label->labelID) {
						$isValid = true;
						break;
					} 
				}
				
				if (!$isValid) {
					throw new UserInputException('labelIDs');
				}
			}
		}
	}
	
	/**
	 * Assigns labels for a article.
	 */
	public function assignLabel() {
		// remove previous labels (if any)
		$labelIDs = array();
		foreach ($this->labelList as $label) {
			$labelIDs[] = $label->labelID;
		}
		
		$conditions = new PreparedStatementConditionBuilder();
		$conditions->add("articleID IN (?)", array($this->parameters['articleIDs']));
		$conditions->add("labelID IN (?)", array($labelIDs));
		
		$sql = "DELETE FROM	wiki".WCF_N."_article_label_to_object
			".$conditions;
		$statement = WCF::getDB()->prepareStatement($sql);
		$statement->execute($conditions->getParameters());
		
		// assign label ids
		if (!empty($this->parameters['labelIDs'])) {
			$sql = "INSERT INTO	wiki".WCF_N."_article_label_to_object
						(labelID, articleID)
				VALUES		(?, ?)";
			$statement = WCF::getDB()->prepareStatement($sql);
			
			WCF::getDB()->beginTransaction();
			foreach ($this->parameters['labelIDs'] as $labelID) {
				foreach ($this->parameters['articleIDs'] as $articleID) {
					$statement->execute(array(
						$labelID,
						$articleID
					));
				}
			}
			WCF::getDB()->commitTransaction();
		}
		
		return array(
			'actionName' => 'assignLabel',
			'labelIDs' => $this->parameters['labelIDs']
		);
		
		$this->unmarkAll();
	}
	
	/**
	 * @see wcf\data\IClipboardAction::validateUnmarkAll()
	 */
	public function validateUnmarkAll() {
		// does nothing
	}
	
	/**
	 * @see wcf\data\IClipboardAction::unmarkAll()
	 */
	public function unmarkAll() {
		ClipboardHandler::getInstance()->removeItems(ClipboardHandler::getInstance()->getObjectTypeID('com.woltnet.wiki.article'));
	}
}
