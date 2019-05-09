<?php
require_once 'Hush/Db/Dao.php';

class Core_Dao extends Hush_Db_Dao
{
	/**
	 * Autoload App Daos
	 * 
	 * @param string $class_name
	 * @return App_Dao
	 */
	public static function load ($class_name)
	{
	    static $_daos = array();
	    if (!isset($_daos[$class_name])) {
	    	require_once 'App/Dao/' . str_replace('_', '/', $class_name) . '.php';
	    	$_daos[$class_name] = new $class_name();
	    }
	    return $_daos[$class_name];
	}
}