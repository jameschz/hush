<?php
/**
 * Ihush Cli
 *
 * @category   Ihush
 * @package    Ihush_Cli
 * @author     James.Huang <huangjuanshi@snda.com>
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
		echo "hush db [backup|recover] <default:0:master:0:ihush_apps>\n";
	}
	
	public function backupAction () 
	{
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
		
		// dumper db data
		$backup_sql = implode('-', $p);
		$dumper_cmd = str_replace(
			array('{PARAMS}', '{SQLFILE}'), 
			array($this->_getCmdParams($p[4]), $this->_getSqlBackup($backup_sql)),
			__MYSQL_DUMPER_COMMAND);
		
		echo "Run Command : $dumper_cmd\n";
		system($dumper_cmd, $dumper_res);
		
		if (!$dumper_res) {
			echo "Backup database ok.\n";
		} else {
			echo "Backup database failed.\n";
		}
	}
	
	public function recoverAction ()
	{
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
		
			// dumper db data
		$backup_sql = implode('-', $p);
		$import_cmd = str_replace(
			array('{PARAMS}', '{SQLFILE}'), 
			array($this->_getCmdParams($p[4]), $this->_getSqlBackup($backup_sql)),
			__MYSQL_IMPORT_COMMAND);
		
		echo "Run Command : $import_cmd\n";
		system($import_cmd, $import_res);
		
		if (!$import_res) {
			echo "Recover database ok.\n";
		} else {
			echo "Recover database failed.\n";
		}
	}
}
