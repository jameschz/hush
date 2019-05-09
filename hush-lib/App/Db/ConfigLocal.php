<?php
/**
 * MySQL config file
 */
require_once 'App/Db/Config.php';

// public db settings
define('__HUSH_DB_TYPE', Hush_Db_Config::DEFAULT_TYPE);
define('__HUSH_DB_HOST', Hush_Db_Config::DEFAULT_HOST);
define('__HUSH_DB_PORT', Hush_Db_Config::DEFAULT_PORT);
define('__HUSH_DB_USER', Hush_Db_Config::DEFAULT_USER);
define('__HUSH_DB_PASS', Hush_Db_Config::DEFAULT_PASS);

class App_Db_ConfigLocal extends App_Db_Config
{
	// same with the class name
	protected static $_configName = 'App_Db_Config';
	
	// cluster configs
	protected $_clusters = array
	(
		// default db
		'default' => array(
			// cluster 0
			array(
				'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
				'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
			),
			// cluster N ...
		),
	    // main log db
	    'hush_log' => array(
	        // cluster 0
	        array(
	            'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	            'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	        ),
	        // cluster N ...
	    ),
	    // shard log db
	    'hush_log_*' => array(
	        // cluster 0
	        array(
	            'master' => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	            'slave'  => array(array('type' => __HUSH_DB_TYPE, 'host' => __HUSH_DB_HOST, 'port' => __HUSH_DB_PORT, 'user' => __HUSH_DB_USER, 'pass' => __HUSH_DB_PASS)),
	        ),
	        // cluster N ...
	    ),
	);
}
