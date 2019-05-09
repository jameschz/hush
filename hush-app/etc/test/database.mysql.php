<?php
/**
 * MySQL config file
 */
require_once 'App/Db/ConfigTest.php';

class MysqlConfig extends App_Db_ConfigTest
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
