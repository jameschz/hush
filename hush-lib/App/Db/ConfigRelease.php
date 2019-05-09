<?php
/**
 * MySQL config file
 */
require_once 'App/Db/Config.php';

// public db settings
define('__HUSH_DB_TYPE', Hush_Db_Config::DEFAULT_TYPE);
define('__HUSH_DB_PORT', '3306');
define('__HUSH_DB_USER', 'root');
define('__HUSH_DB_PASS', 'passwd');

// db cluster settings
define('__CORE_HOST', '172.17.16.11');
define('__LOGS_HOST', '172.17.16.11');

class App_Db_ConfigRelease extends App_Db_Config
{
	// same with the class name
	protected static $_configName = 'App_Db_ConfigRelease';
	
	// cluster configs
	protected $_clusters = array
	(
		// default db
		'default' => array(
			// cluster 0
			array(
			    'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __CORE_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
			    'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __CORE_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
			),
			// cluster N ...
		),
	    // main log db
	    'hush_log' => array(
	        // cluster 0
	        array(
	            'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __LOGS_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	            'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __LOGS_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	        ),
	        // cluster N ...
	    ),
	    // shard log db
	    'hush_log_*' => array(
	        // cluster 0
	        array(
	            'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __LOGS_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	            'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __LOGS_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	        ),
	        // cluster N ...
	    ),
	);
}
