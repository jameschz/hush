<?php
/**
 * MySQL config file
 */
require_once 'App/Db/ConfigRelease.php';

class MysqlConfig extends App_Db_ConfigRelease
{
	/**
	 * @var string
	 */
	protected static $_configName = 'MysqlConfig';
	
	/**
	 * @return Hush_Db_Config
	 */
	public static function getInstance () 
	{
		static $dbConfig;
		if ($dbConfig == null) {
			$dbConfig = new MysqlConfig();
		}
		return $dbConfig;
	}
}
