<?php
namespace wiki\system\clipboard\action;
use wcf\system\clipboard\ClipboardEditorItem;
use wcf\system\clipboard\action\IClipboardAction;
use wcf\system\exception\SystemException;
use wcf\system\WCF;

/**
 * Prepares clipboard editor items for articles.
 * 
 * @author	Rene Gessinger (NurPech), Jean-Marc Licht
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.clipboard.action
 * @category	WoltNet Wiki
 */
class ArticleClipboardAction implements IClipboardAction {
	/**
	 * list of conversations
	 * 
	 * @var	array<wiki\data\article\Article>
	 */
	public $articles = null;
	
	/**
	 * @see	wcf\system\clipboard\action\IClipboardAction::getTypeName()
	 */
	public function getTypeName() {
		return 'com.woltnet.wiki.article';
	}
	
	/**
	 * @see	wcf\system\clipboard\action\IClipboardAction::getClassName()
	 */
	public function getClassName() {
		return 'wiki\data\article\ArticleAction';
	}
	
	/**
	 * @see	wcf\system\clipboard\action\IClipboardAction::execute()
	 */
	public function execute(array $objects, $actionName, array $typeData = array()) {
		// check if no article was accessible
		if (empty($this->articles)) {
			$this->articles = $this->loadCategories($objects);
		}
		
		$item = new ClipboardEditorItem();
		
		switch ($actionName) {
			case 'assignLabel':
				// check if user has labels
				$sql = "SELECT	COUNT(*) AS count
					FROM	wiki".WIKI_N."_article_label
					WHERE	userID = ?";
				$statement = WCF::getDB()->prepareStatement($sql);
				$statement->execute(array(WCF::getUser()->userID));
				$row = $statement->fetchArray();
				if ($row['count'] == 0) {
					return null;
				}
				
				$item->addParameter('objectIDs', array_keys($this->articles));
				$item->setName('article.assignLabel');
			break;
			
			case 'enable':
				$articleIDs = $this->validateEnable();
				if (empty($articleIDs)) {
					return null;
				}
				
				$item->addParameter('objectIDs', $articleIDs);
				$item->addParameter('actionName', 'enable');
				$item->addParameter('className', 'wiki\data\article\ArticleAction');
				$item->setName('article.enable');
			break;
			
			default:
				throw new SystemException("Unknown action '".$actionName."'");
			break;
		}
		
		return $item;
	}
	
	/**
	 * validates articles for enabling and returns ids.
	 *
	 * @TODO: implement permission check
	 * @return	array<integer>
	 */
	public function validateEnable() {
		$articleIDs = array();
		
		foreach ($this->articles as $article) {
			if ($article->isActive) {
				$articleIDs[] = $article->articleID;
			}
		}
		
		return $articleIDs;
	}
	
	/**
	 * load categories for given articles
	 * 
	 * @param	array<wiki\data\article\Article>	$articles
	 * @return	array<wiki\data\article\Article>
	 */
	protected function loadCategories(array $articles) {
		// TODO: get categories to article
		
		return $articles;
	}
	
	/**
	 * @see	wcf\system\clipboard\action\IClipboardAction::getEditorLabel()
	 */
	public function getEditorLabel(array $objects) {
		return WCF::getLanguage()->getDynamicVariable('wiki.clipboard.label.article.marked', array('count' => count($objects)));
	}
}
