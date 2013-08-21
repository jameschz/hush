<?php
/**
 * Ihush Dao
 *
 * @category   Ihush
 * @package    Ihush_Dao
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Db/Dao.php';

/**
 * @abstract
 * @package Ihush_Dao
 */
class Ihush_Dao extends Hush_Db_Dao
{
	/**
	 * Autoload Ihush Daos
	 * 
	 * @param string $class_name
	 * @return Ihush_Dao
	 */
	public static function load ($class_name)
	{
	    static $_daos = array();
	    if(!isset($_daos[$class_name])) {
	    	require_once 'Ihush/Dao/' . str_replace('_', '/', $class_name) . '.php';
	    	$_daos[$class_name] = new $class_name();
	    }
	    return $_daos[$class_name];
	}
}