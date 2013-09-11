<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_View
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Zend_View
 */
require_once 'Zend/View.php';

/**
 * @see Zend_View_Interface
 */
require_once 'Zend/View/Interface.php';

/**
 * @package Hush_View
 */
class Hush_View extends Zend_View
{
	/**
	 * @staticvar Hush_View
	 */
	public static $engine = null;
	
	/**
	 * Method for singleton mode
	 * Return Hush_View instance such as Hush_View_Smarty
	 * @static
	 * @param string $engine
	 * @param array $configs Configurations used by Hush_View subclass
	 * @return Hush_View
	 */
	public static function getInstance($engine, $configs)
	{
		if (!$engine) {
			require_once 'Hush/Exception.php';
			throw new Hush_Exception('View & Template Engine can not be empty');
		}
		if (!self::$engine) {
			$file_name = 'Hush/View/' . ucfirst($engine) . '.php';
			$class_name = 'Hush_View_' . ucfirst($engine);
			
			require_once $file_name;
			self::$engine = new $class_name($configs);
		}
		return self::$engine;
	}
}