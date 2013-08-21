<?php
/**
 * Ihush Mongo
 *
 * @category   Ihush
 * @package    Ihush_Mongo
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Mongo.php';

/**
 * @package Ihush_Mongo
 */
class Ihush_Mongo extends Hush_Mongo
{
	/**
	 * Autoload Ihush Mongos
	 * 
	 * @param string $class_name
	 * @return Ihush_Dao
	 */
	public static function load ($class_name)
	{
	    static $_mongos = array();
	    if(!isset($_mongos[$class_name])) {
	    	require_once 'Ihush/Mongo/' . str_replace('_', '/', $class_name) . '.php';
	    	$_mongos[$class_name] = new $class_name();
	    }
	    return $_mongos[$class_name];
	}
}
