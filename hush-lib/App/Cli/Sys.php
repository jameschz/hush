<?php
/**
 * App Cli
 *
 * @category   App
 * @package    App_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

//require_once 'Hush/Db/Config.php';

/**
 * @package App_Cli
 */
class App_Cli_Sys extends App_Cli
{
	public function __init ()
	{
		parent::__init();
	}
	
	public function helpAction ()
	{
		// command description
		$this->_printHeader();
		echo "hush sys init\n";
		echo "hush sys menu\n";
		echo "hush sys newapp\n";
		echo "hush sys newdao\n";
		echo "hush sys newctrl\n";
		echo "hush sys uplib [core|hush]\n";
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
2. Check all the runtime environment variables and directories.
3. Clean all caches and runtime data.

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
			$this->uplibAction('core');
		}
		
		// check dirs and configs
		if (__OS_WIN) {
			system('./hush check dirs');
			system('./hush check configs');
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
	
	public function menuAction ()
	{
	    echo
	    <<<NOTICE
	    
**********************************************************
* Start to initialize the FzGame Menu System             *
**********************************************************

Please select your system scope from core :)

Please enter your system scope :
NOTICE;
	    
	    // check init task
	    $scope = trim(fgets(fopen("php://stdin", "r")));
	    if (!$scope) {
	        exit("\nPlease enter system scope.\n");
	    }
	    
	    // init system.ini
	    $res = file_put_contents(__SYSTEM_INI, "scope={$scope}");
	    if (!$res) {
	        exit("\nInit system.ini failed.\n");
	    } else {
	        echo "\nInit system.ini ok.\n";
	    }
	    
	    // init dao
	    $this->dao();
	    
	    // init admin db
	    require_once 'App/Util/Menu.php';
	    $menu = new App_Util_Menu();
	    $menu->setScope($scope);
	    $menu->initMenu();
	    $menu->writeDb();
	    echo "\nInit menu data ok.\n";
	    
	    // init libraries
	    $zendDir = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . 'Zend';
	    if (!is_dir($zendDir)) {
	        $this->uplibAction('core');
	    }
	    
	    // init data
	    
	    echo
	    <<<NOTICE
	    
**********************************************************
* Initialized successfully                               *
**********************************************************

Thank you for using Hush Framework !!!

NOTICE;
	}
	
	public function uplibAction ()
	{
		$args = func_get_args();
		$actionName = isset($args[0]) ? $args[0] : null;
		$validActions = array('core', 'hush');
		if (!in_array($actionName, $validActions)) {
			echo "hush sys uplib [core|hush]\n";
			return false;
		}
		
		// see in etc/global.config.php
		$libDir = __COMM_LIB_DIR;
		if (!is_dir($libDir)) {
			mkdir($libDir, 0777, true);
		}
		
		require_once 'Hush/Util/Download.php';
		$down = new Hush_Util_Download();
		
		if ($actionName == 'hush') {
			
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
		
		if ($actionName == 'core') {
		
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
			
			// download Phpdoc
			echo "\nInstalling Php Excel ..\n";
			$downFile = $GLOBALS['LIB']['PHPEXCEL'];
			$saveFile = $libDir . DIRECTORY_SEPARATOR . 'PHPExcel.zip';
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
		    echo "\nLOCALPATH must be a existed dir.\n";
		    exit;
		} else {
		    $localpath = realpath($localpath);
		    echo "\nNEW APP CODE DIR : $localpath\n\n";
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
		
		// check code dirs
		$this->ns_old = __APP_NS;
		$this->ns_new = $namespace;
		$root_dir = realpath(__ROOT . '/../');
		if (!$root_dir || !$localpath) {
		    echo "\nSOURCE or TARGET dirs error.\n";
		    exit;
		} else {
		    mkdir($localpath . '/' . $this->ns_new . '-app', 0777, true);
		    mkdir($localpath . '/' . $this->ns_new . '-lib', 0777, true);
		    mkdir($localpath . '/' . $this->ns_new . '-run/bin', 0777, true);
		}
		
		// copy source code
		$code_cp_dirs = array(
		    $root_dir . '/' . $this->ns_old . '-app' => $localpath . '/' . $this->ns_new . '-app',
		    $root_dir . '/' . $this->ns_old . '-lib' => $localpath . '/' . $this->ns_new . '-lib',
		    $root_dir . '/' . $this->ns_old . '-run/bin' => $localpath . '/' . $this->ns_new . '-run/bin',
		    $root_dir . '/.settings' => $localpath . '/.settings',
		);
		foreach ($code_cp_dirs as $src_dir => $dst_dir) {
		    Hush_Util::dir_copy($src_dir, $dst_dir, array('.svn','.git'), array($this, 'copy_wrapper'));
		}
		copy($root_dir . '/.gitignore', $localpath . '/.gitignore');
		
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
	public function copy_wrapper ($src, $dst)
	{
	    // 忽略的目录
	    $escape_dirs = array(
	        $this->ns_old. '-run/cdn',
	        $this->ns_old. '-run/dat',
	        $this->ns_old. '-run/run',
	        $this->ns_old. '-run/sys',
	    );
	    foreach ($escape_dirs as $escape_dir) {
	        if (preg_match('/'.preg_quote($escape_dir,'/').'/', $src)) {
	            return false;
	        }
	    }
	    
		echo "Copy $src\nSave $dst\n";
		
		// 替换的目录
		$replace_dirs = array(
		    'doc/sql/',
		    'App/Dao/',
		    'App/Cache/',
		    'Core/Session.php',
		    'global.appcfg.php',
		    'global.config.php',
		    'build.bat',
		    'build.sh',
		);
		foreach ($replace_dirs as $replace_dir) {
		    if (preg_match('/'.preg_quote($replace_dir,'/').'/', $src)) {
		        $src_code = file_get_contents($src);
		        $dst_code = str_replace($this->ns_old, $this->ns_new, $src_code);
		        if ($src_code != $dst_code) {
		            $dst_dir_name = dirname($dst);
		            $dst_file_name = basename($dst);
		            $dst_file_name = str_replace($this->ns_old, $this->ns_new, $dst_file_name);
		            $dst_file_path = $dst_dir_name . '/' . $dst_file_name;
		            unlink($dst); // 删除老的文件，创建新的文件
		            file_put_contents($dst_file_path, $dst_code);
		            echo "Replace $dst_file_path ...\n";
		        }
		    }
		}
	}
	
	public function newctrlAction ()
	{
		echo 
<<<NOTICE

**********************************************************
* Start to create a new controller                       *
**********************************************************

Please enter settings by following prompting !!!

Controller Namespace (Default\Page\ControllerName) : 
NOTICE;
		
		// check user input
		$ctrlNS = trim(fgets(fopen("php://stdin", "r")));
		if (!preg_match('/^(Default)\\\\(Page|Http)\\\\[A-Za-z]+$/i', $ctrlNS)) {
			echo "\nError Controller Namespace.\n";
			exit;
		}
		$ctrlNSArr = explode('\\', $ctrlNS);
		$baseName = ucfirst($ctrlNSArr[0]);
		$typeName = ucfirst($ctrlNSArr[1]);
		$ctrlName = ucfirst($ctrlNSArr[2]);
		$replaceArr = array('APPNAME', 'CTRLNAME');
		$changedArr = array('App', $ctrlName);
		
		// check controller path
		$ctrlClsBase = realpath(__LIB_DIR . '/App/App/');
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
		$codeCtrlPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.ctrl.php.t';
		file_put_contents($ctrlClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeCtrlPhp)));
		echo "\nNew Controller Class : $ctrlClsFile\n";
		
		// copy tpl
		if (!is_dir($ctrlTplPath)) mkdir($ctrlTplPath, 0777, true);
		$codeCtrlTpl = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.ctrl.tpl.t';
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
		$changedArr = array('App', $dbName, $tableName);
		
		// check dao path
		$daoClsBase = realpath(__ROOT . '/../lib/App/Dao/'); // move to lib dir
		$daoClsPath = $daoClsBase . DIRECTORY_SEPARATOR . $dbName; // should be created
		$daoClsFile = $daoClsPath . DIRECTORY_SEPARATOR . $tableName . '.php';
		$daoDbClsFile = $daoClsBase . DIRECTORY_SEPARATOR . $dbName . '.php';
//		echo $daoClsBase."\n".$daoClsPath."\n".$daoClsFile."\n".$daoDbClsFile."\n";
		
		// create db class
		if (!file_exists($daoDbClsFile)) {
			$codeDbPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.db.php.t';
			file_put_contents($daoDbClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeDbPhp)));
			echo "\nNew DB Class : $daoDbClsFile\n";
		} else {
			echo "\nDB Class : $daoDbClsFile\n";
		}
		
		// create table class
		if (!is_dir($daoClsPath)) mkdir($daoClsPath, 0777, true);
		if (!file_exists($daoClsFile)) {
			$codeTbPhp = __DOC_DIR . DIRECTORY_SEPARATOR . 'tpl' . DIRECTORY_SEPARATOR . 'code.tb.php.t';
			file_put_contents($daoClsFile, str_replace($replaceArr, $changedArr, file_get_contents($codeTbPhp)));
			echo "\nNew Table Class : $daoClsFile\n";
		} else {
			echo "\nTable Class : $daoClsFile\n";
		}
	}
	
}
