<?php
/**
 * MySQL config file
 */
require_once 'App/Db/ConfigDev.php';

class MysqlConfig extends App_Db_ConfigDev
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
