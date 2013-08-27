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

//require_once 'Hush/Db/Config.php';

/**
 * @package Ihush_Cli
 */
class Ihush_Cli_Sys extends Ihush_Cli
{
	public function __init ()
	{
		parent::__init();
		
		$this->init_sql_be = realpath(__ROOT . '/doc/sql/ihush_core.sql');
		$this->init_sql_fe = realpath(__ROOT . '/doc/sql/ihush_apps.sql');
	}
	
	public function helpAction ()
	{
		// command description
		$this->_printHeader();
		echo "hush sys init\n";
		echo "hush sys uplib\n";
		echo "hush sys newapp\n";
		echo "hush sys newdao\n";
		echo "hush sys newctrl\n";
	}
	
	public function initAction () 
	{
		echo 
<<<NOTICE

**********************************************************
* Start to initialize the Hush Framework                 *
**********************************************************

Please pay attention to this action !!!

Because you will do following things :

1. Check or download the related libraries.
2. Import original databases (Please make sure your current databases were already backuped).
3. Check all the runtime environment variables and directories.
4. Clean all caches and runtime data.

Are you sure you want to continue [Y/N] : 
NOTICE;
		
		// check init task
		$input = fgets(fopen("php://stdin", "r"));
		if (strcasecmp(trim($input), 'y')) {
			exit;
		}
		
		// upgrade libraries
		$zendDir = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . 'Zend';
		if (!is_dir($zendDir)) {
			$this->uplibAction(false);
		}
		
		// import backend and frontend
		$dbSettings = $this->_getDatabaseSettings();
		if ($dbSettings) {
			$import_cmd_be = str_replace(
				array('{PARAMS}', '{SQLFILE}'), 
				array($this->_getCmdParams(
					$dbSettings['type'], 
					$dbSettings['host'], 
					$dbSettings['port'], 
					$dbSettings['user'], 
					$dbSettings['pass']), $this->init_sql_be),
				__MYSQL_IMPORT_COMMAND);
			
			$import_cmd_fe = str_replace(
				array('{PARAMS}', '{SQLFILE}'), 
				array($this->_getCmdParams(
					$dbSettings['type'], 
					$dbSettings['host'], 
					$dbSettings['port'], 
					$dbSettings['user'], 
					$dbSettings['pass']), $this->init_sql_fe),
				__MYSQL_IMPORT_COMMAND);
			
			echo "\nExecute Command : $import_cmd_be\n";
			system($import_cmd_be, $be_res);
			
			echo "Execute Command : $import_cmd_fe\n";
			system($import_cmd_fe, $fe_res);
			
			if (!$be_res && !$be_res) {
				echo "\nImport database ok.\n";
			} else {
				echo "\nImport database failed.\n";
				exit;
			}
		} else {
			echo "\nEscape importing database.\n";
		}
		
		// check dirs and configs
		if (__OS_WIN) {
			system('hush check dirs');
			system('hush check configs');
		} else {
			system('./hush check dirs');
			system('./hush check configs');
		}
		
		echo 
<<<NOTICE

**********************************************************
* Initialized successfully                                *
**********************************************************

Thank you for using Hush Framework !!!

NOTICE;
	}
	
	public function uplibAction ($hush = true)
	{
		// see in etc/global.config.php
		$libDir = __COMM_LIB_DIR;
		if (!is_dir($libDir)) {
			mkdir($libDir, 0777, true);
		}
		
		require_once 'Hush/Util/Download.php';
		$down = new Hush_Util_Download();
		
		// download Zend Framework
		echo "\nInstalling Zend Framework ..\n";
		$downFile = $GLOBALS['LIB']['ZEND'];
		$saveFile = $libDir . DIRECTORY_SEPARATOR . 'ZendFramework.zip';
		$savePath = $libDir . DIRECTORY_SEPARATOR . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Phpdoc
		echo "\nInstalling Php Documentor ..\n";
		$downFile = $GLOBALS['LIB']['PHPDOC'];
		$saveFile = $libDir . DIRECTORY_SEPARATOR . 'Phpdoc.zip';
		$savePath = $libDir . DIRECTORY_SEPARATOR . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Smarty_2
		echo "\nInstalling Smarty 2.x ..\n";
		$downFile = $GLOBALS['LIB']['SMARTY2'];
		$saveFile = $libDir . DIRECTORY_SEPARATOR . 'Smarty_2.zip';
		$savePath = $libDir . DIRECTORY_SEPARATOR . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		// download Smarty_3
		echo "\nInstalling Smarty 3.x ..\n";
		$downFile = $GLOBALS['LIB']['SMARTY3'];
		$saveFile = $libDir . DIRECTORY_SEPARATOR . 'Smarty_3.zip';
		$savePath = $libDir . DIRECTORY_SEPARATOR . '.';
		if ($down->download($downFile, $saveFile)) {
			echo "Extracting.. ";
			$zip = new ZipArchive;
			$zip->open($saveFile);
			$zip->extractTo($savePath);
			$zip->close();
			unset($zip);
			echo "Done!\n";
		}
		
		if ($hush) {
			// download Hush Framework
			echo "\nInstalling Hush Framework .. \n";
			$downFile = $GLOBALS['LIB']['HUSH'];
			$saveFile = __HUSH_LIB_DIR . DIRECTORY_SEPARATOR . 'HushFramework.zip';
			$savePath = __HUSH_LIB_DIR . DIRECTORY_SEPARATOR . '.';
			if (_hush_download($downFile, $saveFile)) {
				echo "Extracting.. ";
				$zip = new ZipArchive;
				$zip->open($saveFile);
				$zip->extractTo($savePath);
				$zip->close();
				unset($zip);
				echo "Done!\n";
			}
		}
		
		unset($down);
		return true;
	}
	
	public function newappAction ()
	{
		echo 
<<<NOTICE

**********************************************************
* Start to create a new app copied from this app         *
**********************************************************

Please enter settings by following prompting !!!

NAMESPACE of the new app : 
NOTICE;
		
		// check user input
		$namespace = trim(fgets(fopen("php://stdin", "r")));
		if (!preg_match('/^[A-Za-z]+$/i', $namespace)) {
			echo "\nNAMESPACE must be a letter.\n";
			exit;
		}
		
		echo 
<<<NOTICE
LOCALPATH of the new app : 
NOTICE;
		
		// check user input
		$localpath = trim(fgets(fopen("php://stdin", "r")));
		if (!is_dir($localpath)) {
			mkdir($localpath, 0777, true);
		}
		$localpath = realpath($localpath);
		if ($localpath) {
			echo "\nLOCALPATH : $localpath\n\n";
		}
		
		echo 
<<<NOTICE
Are you sure you want to continue [Y/N] : 
NOTICE;

		// check user input
		$input = fgets(fopen("php://stdin", "r"));
		if (strcasecmp(trim($input), 'y')) {
			exit;
		}
		
		// copy main code
		Hush_Util::dir_copy(__ROOT, $localpath, array('.svn'), array($this, 'copy_all_wrapper'));
		
		// used by copy_lib_wrapper callback
		$this->namespace = $namespace;
		
		// copy lib code
		$baseLibDir = realpath($localpath . '/lib/');
		$oldLibDir = $baseLibDir . DIRECTORY_SEPARATOR . 'Ihush';
		$newLibDir = $baseLibDir . DIRECTORY_SEPARATOR . $namespace;
		Hush_Util::dir_copy($oldLibDir, $newLibDir, null, array($this, 'copy_lib_wrapper'));
		
		// copy etc code
		$baseEtcDir = realpath($localpath . '/etc/');
		$tmpEtcDir = $localpath . DIRECTORY_SEPARATOR . 'etc_tmp';
		Hush_Util::dir_copy($baseEtcDir, $tmpEtcDir, null, array($this, 'copy_lib_wrapper'));
		Hush_Util::dir_copy($tmpEtcDir, $baseEtcDir, null, null);
		
		// copy bin code
		$baseBinDir = realpath($localpath . '/bin/');
		$tmpBinDir = $localpath . DIRECTORY_SEPARATOR . 'bin_tmp';
		Hush_Util::dir_copy($baseBinDir, $tmpBinDir, null, array($this, 'copy_lib_wrapper'));
		Hush_Util::dir_copy($tmpBinDir, $baseBinDir, null, null);
		
		// remove useless dir
		echo "Remove useless dirs ...\n";
		Hush_Util::dir_remove($oldLibDir);
		Hush_Util::dir_remove($tmpEtcDir);
		Hush_Util::dir_remove($tmpBinDir);
		
		// change init configs
		echo "Change init configs ...\n";
		$configFilePath = $baseEtcDir . DIRECTORY_SEPARATOR . 'global.config.php';
		$configFileCode = file_get_contents($configFilePath);
		$pregArr = array(
			'/__COMM_LIB_DIR\',.*?\)/',
			'/__HUSH_LIB_DIR\',.*?\)/',
		);
		$replaceArr = array(
			'__COMM_LIB_DIR\', _hush_realpath(__ROOT . \'/../phplibs\')',
			'__HUSH_LIB_DIR\', _hush_realpath(__ROOT . \'/../phplibs\')',
		);
		$configFileCode = preg_replace($pregArr, $replaceArr, $configFileCode);
		file_put_contents($configFilePath, $configFileCode);
		
		// all completed
		echo 
<<<NOTICE

**********************************************************
* Create successfully                                    *
**********************************************************

Please check new app in '$localpath' !!!

NOTICE;
	}
	
	// used by newappAction
	public function copy_all_wrapper ($src, $dst)
	{
		echo "Copy $src => $dst\n";
	}
	
	// used by newappAction
	public function copy_lib_wrapper ($src, $dst)
	{
		$srcCode = file_get_contents($src);
		$srcCode = str_replace('Ihush', $this->namespace, $srcCode);
		file_put_contents($dst, $srcCode);
		echo "Overwrite $dst ...\n";
	}
	
	public function newctrlAction ()
	{
		echo 
<<<NOTICE

**********************************************************
* Start to create a new controller                       *
**********************************************************

Please enter settings by following prompting !!!

Controller Namespace (Backend\Page\ControllerName) : 
NOTICE;
		
		// check user input
		$ctrlNS = trim(fgets(fopen("php://stdin", "r")));
		if (!preg_match('/^(Backend|Frontend)\\\\(Page|Remote)\\\\[A-Za-z]+$/i', $ctrlNS)) {
			echo "\nError Controller Namespace.\n";
			exit;
		}
		$ctrlNSArr = explode('\\', $ctrlNS);
		$baseName = ucfirst($ctrlNSArr[0]);
		$typeName = ucfirst($ctrlNSArr[1]);
		$ctrlName = ucfirst($ctrlNSArr[2]);
		$replaceArr = array('APPNAME', 'CTRLNAME');
		$changedArr = array(__APP_NAME, $ctrlName);
		
		// check controller path
		$ctrlClsBase = realpath(__LIB_DIR . '/' . __APP_NAME . '/App/');
		$ctrlNS = str_replace('\\', DIRECTORY_SEPARATOR, $ctrlNS); // fix bug on linux
		$ctrlClsPath = realpath($ctrlClsBase . '/' . dirname($ctrlNS)); // just for check
		$ctrlClsFile = $ctrlClsPath . DIRECTORY_SEPARATOR . $ctrlName . 'Page.php';
		$ctrlTplBase = realpath(__TPL_DIR . '/' . strtolower($baseName) . '/template/');
		$ctrlTplPath = $ctrlTplBase . DIRECTORY_SEPARATOR . strtolower($ctrlName); // should be created
		$ctrlTplFile = $ctrlTplPath . DIRECTORY_SEPARATOR . 'index.tpl';
//		echo $ctrlClsPath."\n".$ctrlClsFile."\n".$ctrlTplFile."\n";
		if (!is_dir($ctrlClsPath)) {
			echo "\nUnknown Controller.\n";
			exit;
		}
		if (file_exists($ctrlClsFile) && file_exists($ctrlTplFile)) {
			echo "\nExisted Controller.\n";
			exit;
		}
		
		// copy code
		$codeCtrlPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.ctrl.php';
		file_put_contents($ctrlClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeCtrlPhp)));
		echo "\nNew Controller Class : $ctrlClsFile\n";
		
		// copy tpl
		if (!is_dir($ctrlTplPath)) mkdir($ctrlTplPath, 0777, true);
		$codeCtrlTpl = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.ctrl.tpl';
		file_put_contents($ctrlTplFile, str_replace($replaceArr, $changedArr, file_get_contents($codeCtrlTpl)));
		echo "\nNew Controller Template : $ctrlTplFile\n";
	}
	
	public function newdaoAction ()
	{
		echo 
<<<NOTICE

**********************************************************
* Start to create a new dao                              *
**********************************************************

Please enter settings by following prompting !!!

Dao Namespace (SimpleDatabaseName\SimpleTableName) : 
NOTICE;
		
		// check user input
		$daoNS = trim(fgets(fopen("php://stdin", "r")));
		if (!preg_match('/^[A-Za-z]+\\\\[A-Za-z]+$/i', $daoNS)) {
			echo "\nSimple Database & Table Name must be a letter. Just give a simple name here and you can set the real name in the code.\n";
			exit;
		}
		$daoNSArr = explode('\\', $daoNS);
		$dbName = ucfirst($daoNSArr[0]);
		$tableName = ucfirst($daoNSArr[1]);
		$replaceArr = array('APPNAME', 'DBNAME', 'TABLENAME');
		$changedArr = array(__APP_NAME, $dbName, $tableName);
		
		// check dao path
		$daoClsBase = realpath(__LIB_DIR . '/' . __APP_NAME . '/Dao/');
		$daoClsPath = $daoClsBase . DIRECTORY_SEPARATOR . $dbName; // should be created
		$daoClsFile = $daoClsPath . DIRECTORY_SEPARATOR . $tableName . '.php';
		$daoDbClsFile = $daoClsBase . DIRECTORY_SEPARATOR . $dbName . '.php';
//		echo $daoClsBase."\n".$daoClsPath."\n".$daoClsFile."\n".$daoDbClsFile."\n";
		
		// create db class
		if (!file_exists($daoDbClsFile)) {
			$codeDbPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.db.php';
			file_put_contents($daoDbClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeDbPhp)));
			echo "\nNew DB Class : $daoDbClsFile\n";
		} else {
			echo "\nDB Class : $daoDbClsFile\n";
		}
		
		// create table class
		if (!is_dir($daoClsPath)) mkdir($daoClsPath, 0777, true);
		if (!file_exists($daoClsFile)) {
			$codeTbPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.tb.php';
			file_put_contents($daoClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeTbPhp)));
			echo "\nNew Table Class : $daoClsFile\n";
		} else {
			echo "\nTable Class : $daoClsFile\n";
		}
	}
}
