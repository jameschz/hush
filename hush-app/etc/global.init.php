<?php
/**
 * Global settings
 */
$GLOBALS['LIB']['HUSH'] = 'http://fz-cdn.focusgames.cn/hush/HushFramework.zip';
$GLOBALS['LIB']['ZEND'] = 'http://fz-cdn.focusgames.cn/hush/ZendFramework.zip';
$GLOBALS['LIB']['SMARTY2'] = 'http://fz-cdn.focusgames.cn/hush/Smarty_2.zip';
$GLOBALS['LIB']['SMARTY3'] = 'http://fz-cdn.focusgames.cn/hush/Smarty_3.zip';
$GLOBALS['LIB']['PHPDOC'] = 'http://fz-cdn.focusgames.cn/hush/Phpdoc.zip';
$GLOBALS['LIB']['PHPEXCEL'] = 'http://fz-cdn.focusgames.cn/hush/PHPExcel.zip';

/**
 * Global checking
 */
if (!class_exists('ZipArchive')) {
	echo "Please install zip extension for PHP\n";
	exit;
}

/**
 * Global env variables
 */
if (!file_exists(__ETC.'/.env.php')) {
    echo "Please config .env.php file from env.php.sample\n";
    exit;
}
$_HUSH = require_once __ETC.'/.env.php';
$GLOBALS['HUSH'] = $_HUSH;
define('__HUSH_ENV', $_HUSH['ENV']);
define('__HUSH_ENVID', $_HUSH['ENVID']);

/**
 * Global include path
 */
define('__HUSH_LIB_DIR', _hush_realpath(__ROOT . '/../' . __APP_NS . '-lib'));
define('__COMM_LIB_DIR', !empty($_HUSH['PHPLIBS']) ? $_HUSH['PHPLIBS'] : _hush_realpath(__ROOT . '/../../phplibs'));
set_include_path('.' . PATH_SEPARATOR . __LIB_DIR . PATH_SEPARATOR . __HUSH_LIB_DIR . PATH_SEPARATOR . __COMM_LIB_DIR . PATH_SEPARATOR . get_include_path());

/**
 * Global initialization
 */
if (defined('__HUSH_CLI')) {
	
	// check path
	if (!is_dir(__COMM_LIB_DIR)) {
		mkdir(__COMM_LIB_DIR, 0777, true);
	}
	if (!is_dir(__HUSH_LIB_DIR)) {
		mkdir(__HUSH_LIB_DIR, 0777, true);
	}

	// check core libraries
	$zendDir = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . 'Zend';
	if (!is_dir($zendDir)) {
	    // close error
	    error_reporting(0);
	    // download Core Framework
	    $coreLibs = array('ZEND', 'SMARTY3');
	    foreach ($coreLibs as $coreLibName) {
	        echo "\nInstalling {$coreLibName} Framework .. \n";
	        $downFile = $GLOBALS['LIB'][$coreLibName];
	        $saveFile = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . basename($downFile);
	        $savePath = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . '.';
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
	}
	
	// check hush libraries
	$hushDir = __HUSH_LIB_DIR . DIRECTORY_SEPARATOR . 'Hush';
	if (!is_dir($hushDir)) {
	    // close error
	    error_reporting(0);
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
}
else {
	
	// check other libraries
	$hushDir = __HUSH_LIB_DIR . DIRECTORY_SEPARATOR . 'Hush';
	$zendDir = __COMM_LIB_DIR . DIRECTORY_SEPARATOR . 'Zend';
	if (!is_dir($hushDir) || !is_dir($zendDir)) {
		echo "Core libraries can not be found .. <br/>\n";
		echo "If it is the first installation, Please do the following :";
		echo "1. Please enter 'hush-app/bin' and execute 'hush sys uplib' command.<br/>\n";
		echo "2. If database NO-EXISTED, Please execute 'hush db init create' and 'hush db table create' command.<br/>\n";
		exit(1);
	}
}