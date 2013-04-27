<?php
namespace wiki\data\article;
use wiki\data\article\label\ArticleLabel;

use wcf\system\WCF;
use wcf\data\DatabaseObjectDecorator;
use wcf\system\visitTracker\VisitTracker;
use wcf\data\user\User;
use wcf\data\user\UserProfile;

/**
 * @author	Rene Gessinger (NurPech)
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	data.article
 * @category 	WoltNet - Wiki
 */
class ViewableArticle extends DatabaseObjectDecorator {
  /**
   * @see	wcf\data\DatabaseObjectDecorator::$baseClass
   */
  protected static $baseClass = 'wiki\data\article\Article';

  /**
   * user profile object
   *
   * @var	wcf\data\user\UserProfile
   */
  protected $userProfile = null;

  /**
   * effective visit time
   *
   * @var integer
   */
  protected $effectiveVisitTime = null;

  /**
   * list of assigned labels
   *
   * @var	array<wiki\data\article\label\ArticleLabel>
   */
  protected $labels = array();


  /**
   * Returns the user profile object.
   *
   * @return	wcf\data\user\UserProfile
   */
  public function getAuthor() {
    if ($this->userProfile === null) {
      $this->userProfile = new UserProfile(new User(null, $this->getDecoratedObject()->data));
    }

    return $this->userProfile;
  }

  /**
   * Alias for wiki\data\article\ViewableArticle::getAuthor()
   * @return \wcf\data\user\UserProfile
   */
  public function getUserProfile() {
  	return $this->getAuthor();
  }

  /**
   * Gets a specific article decorated as viewable article.
   *
   * @param integer $articleID
   * @return wiki\data\article\ViewableArticleList
   */
  public static function getViewableArticle($articleID) {
    $list = new ViewableArticleList();
    $list->getConditionBuilder()->add('article.articleID = ?', array($articleID));
    $list->readObjects();
    $objects = $list->getObjects();
    if (isset($objects[$articleID])) return $objects[$articleID];
    return null;
  }

  /**
   * Returns the effective visit time.
   *
   * @return integer
   */
  public function getVisitTime() {
    if ($this->effectiveVisitTime === null) {
      if (WCF::getUser()->userID) {
        $this->effectiveVisitTime = max($this->visitTime, $this->categoryVisitTime, VisitTracker::getInstance()->getVisitTime('com.woltnet.wiki.article'));
      }
      else {
        $this->effectiveVisitTime = max(VisitTracker::getInstance()->getObjectVisitTime('com.woltnet.wiki.article', $this->articleID), VisitTracker::getInstance()->getObjectVisitTime('com.woltnet.wiki.category', $this->categoryID), VisitTracker::getInstance()->getVisitTime('com.woltnet.wiki.article'));
      }
      if ($this->effectiveVisitTime === null) {
        $this->effectiveVisitTime = 0;
      }
    }

    return $this->effectiveVisitTime;
  }

  /**
   * Returns true, if this project is new for the active user.
   *
   * @return boolean
   */
  public function isNew() {
    if ($this->time > $this->getVisitTime()) {
      return true;
    }

    return false;
  }

  /**
   * Assigns a label.
   *
   * @param	wiki\data\article\label\ArticleLabel	$label
   */
  public function assignLabel(ArticleLabel $label) {
    $this->labels[$label->labelID] = $label;
  }

  /**
   * Returns a list of assigned labels.
   *
   * @return	array<wiki\data\article\label\ArticleLabel>
   */
  public function getAssignedLabels() {
    return $this->labels;
  }
}
