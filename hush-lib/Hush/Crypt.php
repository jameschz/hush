<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Crypt
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @package Hush_Crypt
 */
abstract class Hush_Crypt
{
	/**
	 * Create crypt object
	 * @param string $engine Engine name (rsa)
	 * @return object
	 */
	public static function factory ($engine, $config = array())
	{
		$class_file = 'Hush/Crypt/' . ucfirst($engine) . '.php';
		$class_name = 'Hush_Crypt_' . ucfirst($engine);
		
		require_once $class_file; // include crypt class
		
		return new $class_name($config);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// abstract methods 
	
	abstract public function encrypt ($s);
	
	abstract public function decrypt ($s);
}