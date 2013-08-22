<?php
/**
 * Lbs Cli
 *
 * @category   Lbs
 * @package    Lbs_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Cli.php';

/**
 * @package Lbs_Cli
 */
class Ihush_Cli extends Hush_Cli
{	
	/**
	 * Implement Hush_Cli run method
	 * Used by bin/cli.php
	 * @return void
	 */
	public function run ()
	{
		$classFile = 'Ihush/Cli/' . ucfirst(strtolower($this->_className)) . '.php';
		$className = 'Ihush_Cli_' . ucfirst(strtolower($this->_className));
		@require_once $classFile;
		if (class_exists($className)) {
			$cli = new $className();
			$cli->start();
		}
	}
	
	/**
	 * Print header
	 */
	protected function _printHeader ()
	{
		// print command list
		echo "\n----------------------------------------------------------\n";
		echo "Cli Class : " . get_class($this) . "\n";
		echo "----------------------------------------------------------\n\n";
	}
	
	/**
	 * Load config file
	 * @param string $env
	 */
	protected function _loadConfig ($env)
	{
		switch ($env) {
			case 'fe':
				require_once __ETC_DIR . '/frontend.config.php';
				break;
			case 'be':
				require_once __ETC_DIR . '/backend.config.php';
				break;
			default:
				break;
		}
	}
	
	/**
	 * Get config file
	 * @return array
	 */
	protected function _getDatabaseSettings ()
	{
		echo "\nPlease enter your database settings :\n";
		echo "\nDatabase type (mysql) :";
		$dbType = trim(fgets(fopen("php://stdin", "r")));
		$dbType = $dbType ? $dbType : 'mysql';
		
		echo "Database host (localhost) :";
		$dbHost = trim(fgets(fopen("php://stdin", "r")));
		$dbHost = $dbHost ? $dbHost : 'localhost';
		
		echo "Database port (3306) :";
		$dbPort = trim(fgets(fopen("php://stdin", "r")));
		$dbPort = $dbPort ? $dbPort : '3306';
		
		echo "Database username (root) :";
		$dbUser = trim(fgets(fopen("php://stdin", "r")));
		$dbUser = $dbUser ? $dbUser : 'root';
		
		echo "Database password (passwd) :";
		$dbPass = trim(fgets(fopen("php://stdin", "r")));
		$dbPass = $dbPass ? $dbPass : 'passwd';
		
		echo
		<<<NOTICE
		
Database settings as follow :
		
Database type : {$dbType}
Database host : {$dbHost}
Database port : {$dbPort}
Database username : {$dbUser}
Database password : {$dbPass}
		
Are you sure you want to import database [Y/N] :
NOTICE;
		
		// check import database
		$input = fgets(fopen("php://stdin", "r"));
		if (!strcasecmp(trim($input), 'y')) {
			return array(
				'type' => $dbType,
				'host' => $dbHost,
				'port' => $dbPort,
				'user' => $dbUser,
				'pass' => $dbPass,
			);
		}
		
		return array();
	}
	
	/**
	 * Get config file
	 * @param string $dbName
	 * @param string $dbType
	 * @return string
	 */
	protected function _getCmdParams ($dbType = 'mysql', $dbHost = '', $dbPort = '', $dbUser = '', $dbPass = '', $dbName = '')
	{
		$dbType = $dbType ? $dbType : 'mysql';
		$dbHost = $dbHost ? $dbHost : Hush_Db_Config::DEFAULT_HOST;
		$dbPort = $dbPort ? $dbPort : Hush_Db_Config::DEFAULT_PORT;
		$dbUser = $dbUser ? $dbUser : Hush_Db_Config::DEFAULT_USER;
		$dbPass = $dbPass ? $dbPass : Hush_Db_Config::DEFAULT_PASS;
		$dbName = $dbName ? $dbName : '';
		
		switch ($dbType) {
			case 'mysql':
				return ' -h' . $dbHost
					 . ' -P' . $dbPort
					 . ' -u' . $dbUser
					 . ' -p"' . $dbPass
					 . '" ' . $dbName;
			default:
				return '';
		}
	}
	
	/**
	 * Get backup sql file
	 * @param string $dbName
	 * @param string $dbType
	 * @return string
	 */
	protected function _getSqlBackup ($dbName, $dbType = 'mysql')
	{
		switch ($dbType) {
			case 'mysql':
				return __DAT_DIR . '/dbsql/mysql.' . $dbName . '.' . date('Y-m-d') . '.sql';
			default:
				return '';
		}
	}
}
