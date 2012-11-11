<?php
namespace wiki\acp\page;
use wcf\page\AbstractPage;
use wcf\system\WCF;

/**
 * Display ACP index page
 * 
 * @author	Jean-Marc Licht
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @subpackage	acp.page
 * @category 	WoltNet - Wiki
 */
class IndexPage extends AbstractPage {
	/**
	 * @see wcf\page\AbstractPage::$templateName
	 */
	public $templateName = 'index';
	
	// data
	public $os = '', $webserver = '', $sqlVersion = '', $sqlType = '', $load = '';
	
	/**
	 * @see wcf\page\AbstractPage::readData()
	 */
	public function readData() {
		parent::readData();

		$this->os = PHP_OS;
		if (isset($_SERVER['SERVER_SOFTWARE'])) $this->webserver = $_SERVER['SERVER_SOFTWARE'];
		$this->sqlVersion = WCF::getDB()->getVersion();
		$this->sqlType = WCF::getDB()->getDBType();
		$this->readLoad();
	}
	
	/**
	 * Gets the current server load.
	 */
	protected function readLoad() {
		if ($uptime = @exec("uptime")) {
			if (preg_match("/averages?: ([0-9\.]+,?[\s]+[0-9\.]+,?[\s]+[0-9\.]+)/", $uptime, $match)) {
				$this->load = $match[1];
			}
		}
	}
	
	/**
	 * @see wcf\page\AbstractPage::assignVariables()
	 */
	public function assignVariables() {
		parent::assignVariables();

		WCF::getTPL()->assign(array(
			'os' => $this->os,
			'webserver' => $this->webserver,
			'sqlVersion' => $this->sqlVersion,
			'sqlType' => $this->sqlType,
			'load' => $this->load,
			'dbName' => WCF::getDB()->getDatabaseName()
		));
	}
}
