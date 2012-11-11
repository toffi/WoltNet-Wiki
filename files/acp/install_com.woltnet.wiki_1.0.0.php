<?php
use wcf\system\dashboard\DashboardHandler;
use wcf\system\WCF;

/**
 * @author	Jean-Marc Licht
 * @copyright	2012 WoltNet
 * @license	GNU Lesser General Public License <http://opensource.org/licenses/lgpl-license.php>
 * @package	com.woltnet.wiki
 * @category 	WoltNet - Wiki
 */
$package = $this->installation->getPackage();

// set installation date
$sql = "UPDATE	wcf".WCF_N."_option
	SET	optionValue = ?
	WHERE	optionName = 'install_date'
		AND packageID = ?";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute(array(
	TIME_NOW, 
	$package->packageID
));

// add article column in user table
$columnName = 'wiki'.$package->instanceNo.'Articles';
$editor = WCF::getDB()->getEditor();
$editor->addColumn("wcf".WCF_N."_user", $columnName, array(
	'type' => 'int',
	'length' => 10,
	'notNull' => true,
	'default' => 0
));

// log column
$sql = "INSERT INTO	wcf".WCF_N."_package_installation_sql_log
			(packageID, sqlTable, sqlColumn)
	VALUES		(?, ?, ?)";
$statement = WCF::getDB()->prepareStatement($sql);
$statement->execute(array($package->packageID, "wcf".WCF_N."_user", $columnName));

// dashboard
//DashboardHandler::setDefaultValues('com.woltnet.wdb.IndexPage', array('latestArticles' => 1, 'updatedArticles' => 2));

// try to delete this file
@unlink(__FILE__);
