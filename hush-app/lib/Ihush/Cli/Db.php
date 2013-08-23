<?php
/**
 * Ihush Cli
 *
 * @category   Ihush
 * @package    Ihush_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Ihush_Cli
 */
class Ihush_Cli_Db extends Ihush_Cli
{
	public function __init ()
	{
		parent::__init();
		$this->_printHeader();
		$this->db_config = new MysqlConfig();
	}
	
	public function helpAction ()
	{
		// command description
		echo "hush db [backup|recover] default:0:master:0:ihush_apps\n";
	}
	
	public function backupAction () 
	{
		echo "Try to backup database!!!\n";
		
		// get db name
		$args = func_get_args();
		$selector = isset($args[0]) ? $args[0] : null;
		if (!$selector) return $this->helpAction();
		
		// check input
		$p = explode(':', $selector);
		if (count($p) != 5) {
			die("Error : Invalid input params.\n");
		}
		
		// check db config
		try {
			$c = $this->db_config->getDb($p[0], $p[2], $p[1], $p[3]);
		} catch (Exception $e) {
			die("Error : " . $e->getMessage() . ".\n");
		}
		
		echo
		<<<NOTICE
		
Database settings as follow :
		
Database type : {$c['type']}
Database host : {$c['host']}
Database port : {$c['port']}
Database name : {$p[4]}
Database username : {$c['user']}
Database password : {$c['pass']}
		
Are you sure you want to import database [Y/N] :
NOTICE;
		
		$dumper_res = false;
		
		// check import database
		$input = fgets(fopen("php://stdin", "r"));
		if (!strcasecmp(trim($input), 'y')) {
			// dumper db data
			$backup_sql = implode('-', $p);
			$dumper_cmd = str_replace(
				array('{PARAMS}', '{SQLFILE}'), 
				array($this->_getCmdParams(
					$dbSettings['type'], 
					$dbSettings['host'], 
					$dbSettings['port'], 
					$dbSettings['user'], 
					$dbSettings['pass'],
					$p[4]), $this->_getSqlBackup($backup_sql)),
				__MYSQL_DUMPER_COMMAND);
			
			echo "\nExecute Command : $dumper_cmd\n";
			system($dumper_cmd, $dumper_res);
		}
		
		if (!$dumper_res) {
			echo "\nBackup database ok.\n";
		} else {
			echo "\nBackup database failed.\n";
		}
	}
	
	public function recoverAction ()
	{
		echo "Try to recover database!!!\n";
		
		$args = func_get_args();
		$selector = isset($args[0]) ? $args[0] : null;
		if (!$selector) return $this->helpAction();
		
		// check input
		$p = explode(':', $selector);
		if (count($p) != 5) {
			die("Error : Invalid input params.\n");
		}
		
		// check db config
		try {
			$c = $this->db_config->getDb($p[0], $p[2], $p[1], $p[3]);
		} catch (Exception $e) {
			die("Error : " . $e->getMessage() . ".\n");
		}
		
		echo
		<<<NOTICE
		
Database settings as follow :
		
Database type : {$c['type']}
Database host : {$c['host']}
Database port : {$c['port']}
Database name : {$p[4]}
Database username : {$c['user']}
Database password : {$c['pass']}
		
Are you sure you want to import database [Y/N] :
NOTICE;
		
		$dumper_res = false;
		
		// check import database
		$input = fgets(fopen("php://stdin", "r"));
		if (!strcasecmp(trim($input), 'y')) {
			// dumper db data
			$backup_sql = implode('-', $p);
			$import_cmd = str_replace(
				array('{PARAMS}', '{SQLFILE}'), 
				array($this->_getCmdParams(
					$dbSettings['type'],
					$dbSettings['host'],
					$dbSettings['port'],
					$dbSettings['user'],
					$dbSettings['pass'],
					$p[4]), $this->_getSqlBackup($backup_sql)),
				__MYSQL_IMPORT_COMMAND);
			
			echo "Execute Command : $import_cmd\n";
			system($import_cmd, $import_res);
		}
		
		if (!$import_res) {
			echo "Recover database ok.\n";
		} else {
			echo "Recover database failed.\n";
		}
	}
	
	public function testAction()
	{
		$dao = $this->dao()->load('Core_User');
		$res = $dao->read(1);
		print_r($res);
	}
}
