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
class Ihush_Cli_Check extends Ihush_Cli
{
	public function __init ()
	{
		parent::__init();
		$this->_printHeader();
	}
	
	public function helpAction ()
	{
		// command description
		echo "hush check [dirs|configs]\n";
		echo "hush check all\n";
	}
	
	public function dirsAction () 
	{
		$check_dirs = array(
			__DAT_DIR . '/cache',
			__DAT_DIR . '/dbsql',
			__TPL_DIR . '/backend/cache',
			__TPL_DIR . '/backend/config',
			__TPL_DIR . '/backend/template_c',
			__TPL_DIR . '/frontend/cache',
			__TPL_DIR . '/frontend/config',
			__TPL_DIR . '/frontend/template_c'
		);
		
		foreach ($check_dirs as $dir) {
			if (!is_dir($dir) || !is_writable($dir)) {
				@mkdir($dir, 0777);
			}
			echo "Check Dir : $dir\n";
		}
		
		echo "Check all dirs ok.\n";
	}
	
	public function configsAction () 
	{
		// config classes
		$check_classes = array(
			'MysqlConfig',
			'MongoConfig',
		);
		
		// check classes
		foreach ($check_classes as $class) {
			if (!class_exists($class)) {
				die("Error : bad config class $define.\n");
			}
			echo "Check Config class : $class\n";
		}
		echo "Check all config classes ok.\n";
		
		// config defines
		$check_defines = array(
			'__APP_NAME',
			'__COMM_LIB_DIR',
			'__HUSH_LIB_DIR',
			'__MAP_INI_FILE',
			'__MSG_INI_FILE',
			'__LIB_PATH_PAGE',
			'__TPL_SMARTY_PATH',
			'__FILECACHE_DIR',
		);
		
		// check frontend defines
		$this->_loadConfig('fe');
		foreach ($check_defines as $define) {
			if (!defined($define) || !constant($define)) {
				die("Error : bad define constant $define.\n");
			}
			echo "Check Frontend Define : $define > " . constant($define) . "\n";
		}
		echo "Check frontend defines ok.\n";
		
		// check backend defines
		$this->_loadConfig('be');
		foreach ($check_defines as $define) {
			if (!defined($define) || !constant($define)) {
				die("Error : bad define constant $define.\n");
			}
			echo "Check Backend Define : $define > " . constant($define) . "\n";
		}
		echo "Check backend defines ok.\n";
	}
	
	public function allAction ()
	{
		$this->dirsAction();
		$this->configsAction();
	}
}
