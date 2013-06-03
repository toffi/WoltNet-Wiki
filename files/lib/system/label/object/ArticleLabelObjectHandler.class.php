<?php
namespace wiki\system\label\object;

use wcf\system\label\object\AbstractLabelObjectHandler;

/**
 * Label handler for articles.
 *
 * @author	Jean-Marc Licht
 * @copyright	2012 woltnet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	system.label.object
 * @category 	Woltnet Wiki
 */
class ArticleLabelObjectHandler extends AbstractLabelObjectHandler {
    /**
     * @see	wcf\system\label\object\AbstractLabelObjectHandler::$objectType
     */
    protected $objectType = 'com.woltnet.wiki.article';
}
