<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Process
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @package Hush_Process
 */
abstract class Hush_Process_Storage
{
	/**
	 * Create storage object
	 * @param string $engine Engine name (sysv, file)
	 * @return object
	 */
	public static function factory ($engine, $config = array())
	{
		$class_file = 'Hush/Process/Storage/' . ucfirst($engine) . '.php';
		$class_name = 'Hush_Process_Storage_' . ucfirst($engine);
		
		require_once $class_file; // include storage class
		
		return new $class_name($config);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// abstract methods 
	
	abstract public function get ($k);
	
	abstract public function set ($k, $v);
	
	abstract public function delete ($k);
	
	abstract public function remove ();
}