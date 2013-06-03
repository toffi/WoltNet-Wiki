<?php
namespace wiki\system\search;

use wiki\data\article\SearchResultArticleList;
use wiki\data\category\WikiCategoryNodeTree;
use wcf\form\IForm;
use wcf\system\database\util\PreparedStatementConditionBuilder;
use wcf\system\category\CategoryHandler;
use wcf\system\exception\UserInputException;
use wcf\system\search\AbstractSearchableObjectType;
use wcf\system\WCF;
use wcf\util\ArrayUtil;

/**
 * An implementation of ISearchableObjectType for searching articles.
 *
 * @author Rene Gessinger (NurPech)
 * @copyright 2012 woltnet
 * @package com.woltnet.wiki
 * @subpackage system.search
 * @category WoltNet - Wiki
 */
class ArticleSearch extends AbstractSearchableObjectType {
	/**
	 * message data cache
	 *
	 * @var array
	 */
	public $messageCache = array ();
	
	/**
	 * category ids
	 *
	 * @var array<integer>
	 */
	public $categoryIDs = array ();
	
	/**
	 * list of all categories
	 *
	 * @var array
	 */
	public $categories = array ();
	
	/**
	 * list of selected categories
	 *
	 * @var array
	 */
	public $selectedCategoriess = array ();
	
	/**
	 * shows results as articles
	 *
	 * @var boolean
	 */
	public $findArticles = 1;
	
	/**
	 * objectTypeName for Wiki Categories
	 *
	 * @var string
	 */
	public $objectTypeName = 'com.woltnet.wiki.category';
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::cacheObjects()
	 */
	public function cacheObjects(array $objectIDs, array $additionalData = null) {
		$articleList = new SearchResultArticleList();
		$articleList->getConditionBuilder()->add('article.activeVersionID IS NOT NULL');
		$articleList->getConditionBuilder()->add('article.articleID IN (?)', array (
				$objectIDs 
		));
		$articleList->readObjects();
		foreach($articleList->getObjects() as $article) {
			$this->messageCache[$article->articleID] = $article;
		}
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getJoins()
	 */
	public function getJoins() {
		return 'LEFT JOIN wiki' . WCF_N . '_article_version article_version ON (article_version.versionID = ' . $this->getTableName() . '.activeVersionID)';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getObject()
	 */
	public function getObject($objectID) {
		if(isset($this->messageCache[$objectID]))
			return $this->messageCache[$objectID];
		return null;
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getFormTemplateName()
	 */
	public function getFormTemplateName() {
		return 'searchArticle';
	}
	
	/**
	 *
	 * @see wcf\system\search.AbstractSearchableObjectType::getApplication()
	 */
	public function getApplication() {
		return 'wiki';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getSubjectFieldName()
	 */
	public function getSubjectFieldName() {
		return 'article_version.subject';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getUsernameFieldName()
	 */
	public function getUsernameFieldName() {
		return 'article_version.username';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getTimeFieldName()
	 */
	public function getTimeFieldName() {
		return 'article_version.time';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getTableName()
	 */
	public function getTableName() {
		return 'wiki' . WCF_N . '_article';
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getIDFieldName()
	 */
	public function getIDFieldName() {
		return $this->getTableName() . '.articleID';
	}
	
	/**
	 *
	 * @see @see wcf\system\search\ISearchableObjectType::getAdditionalData()
	 */
	public function getAdditionalData() {
		return array (
				'findArticles' => $this->findArticles,
				'categoryIDs' => $this->categoryIDs 
		);
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::show()
	 */
	public function show(IForm $form = null) {
		// get searchable categories
		$categoryNodeTree = new WikiCategoryNodeTree($this->objectTypeName);
		$categoryNodeList = $categoryNodeTree->getIterator();
		
		// get existing values
		if($form !== null && isset($form->searchData['additionalData']['article'])) {
			$this->articleIDs = $form->searchData['additionalData']['article']['categoryIDs'];
		}
		
		WCF::getTPL()->assign(array (
				'articleIDs' => $this->articleIDs,
				'selectAllCategories' => count($this->categoryIDs) == 0 || $this->categoryIDs[0] == '*',
				'findArticles' => $this->findArticles,
				'categoryList' => $categoryNodeList 
		));
	}
	
	/**
	 * Reads the given form parameters.
	 *
	 * @param wcf\form\IForm $form        	
	 */
	protected function readFormParameters(IForm $form = null) {
		// get existing values
		if($form !== null && isset($form->searchData['additionalData']['article'])) {
			$this->categoryIDs = $form->searchData['additionalData']['article']['categoryIDs'];
		}
		
		// get new values
		if(isset($_POST['categoryIDs']) && is_array($_POST['categoryIDs'])) {
			$this->categoryIDs = ArrayUtil::toIntegerArray($_POST['categoryIDs']);
		}
		
		if(isset($_POST['findArticles'])) {
			$this->findArticles = intval($_POST['findArticles']);
		}
	}
	
	/**
	 *
	 * @see wcf\system\search\ISearchableObjectType::getConditions()
	 */
	public function getConditions(IForm $form = null) {
		$conditionBuilder = new PreparedStatementConditionBuilder();
		$this->readFormParameters($form);
		
		$categoryIDs = $this->categoryIDs;
		if(count($categoryIDs) && $categoryIDs[0] == '*')
			$categoryIDs = array ();
			
			// remove empty elements
		foreach($categoryIDs as $key => $categoryID) {
			if($categoryID == '-')
				unset($categoryIDs[$key]);
		}
		
		// get all categories from cache
		$this->categories = CategoryHandler::getInstance()->getCategories($this->objectTypeName);
		$this->selectedCategories = array ();
		
		// check whether the selected category does exist
		foreach($categoryIDs as $categoryID) {
			if(! isset($this->categories[$categoryID])) {
				throw new UserInputException('categoryIDs', 'notValid');
			}
			
			if(! isset($this->selectedCategories[$categoryID])) {
				$this->selectedCategories[$categoryID] = $this->categories[$categoryID];
			}
		}
		if(count($this->selectedCategories) == 0)
			$this->selectedCategories = $this->categories;
			
			// get category ids
		$categoryIDs = array ();
		if(count($this->selectedCategories) != count($this->categories)) {
			foreach($this->selectedCategories as $category) {
				$categoryIDs[] = $category->categoryID;
			}
		}
		
		// category ids
		if(count($categoryIDs)) {
			$conditionBuilder->add($this->getTableName() . '.categoryID IN (?)', array (
					$categoryIDs 
			));
		}
		
		// language
		if(count(WCF::getUser()->getLanguageIDs())) {
			$conditionBuilder->add('(' . $this->getTableName() . '.languageID IN (?) OR ' . $this->getTableName() . '.languageID IS NULL)', array (
					WCF::getUser()->getLanguageIDs() 
			));
		}
		
		return $conditionBuilder;
	}
}
