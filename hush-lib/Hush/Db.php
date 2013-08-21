<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Db
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Zend_Db
 */
require_once 'Zend/Db.php';

/**
 * @see Hush_Db_Exception
 */
require_once 'Hush/Db/Exception.php';

/**
 * @package Hush_Db
 */
class Hush_Db extends Zend_Db
{
	/**
	 * @static
	 */
	const ADAPTER_NAME_SPACE = 'Hush_Db_Adapter'; // default name space
	
	/**
	 * Db settings pool array
	 * @var array
	 */
	public static $db_pool = array();
	
	/**
	 * Db adaptor factory
	 * @static
	 * @param mixed $adapter Can be 'MYSQLI', 'ORACLE'...
	 * @param array $config
	 * @return Zend_Db_Adaptor
	 */
	public static function factory($adapter, $config = array())
	{
		if (!is_array($config)) {
			throw new Zend_Db_Exception("Adapter parameters must be in an array or a Zend_Config object");
		}
		
		if (!is_string($adapter) || empty($adapter)) {
			throw new Zend_Db_Exception("Adapter name must be specified in a string");
		}
		
		// Load adapter class and new an instance
		$adapterNameSpace = self::ADAPTER_NAME_SPACE;
		$adapterName = $adapterNameSpace . '_' . str_replace(' ', '_', ucwords(str_replace('_', ' ', strtolower($adapter))));
		$adapterFile = str_replace('_', DIRECTORY_SEPARATOR, $adapterName) . '.php';
		if (!class_exists($adapterName)) require_once $adapterFile;
		$dbAdapter = new $adapterName($config);
		
		// Return immediately when implemented with simple interface
		if ($dbAdapter instanceof Hush_Db_Adapter_Simple_Abstract) return $dbAdapter;
		
		// Or must implement Zend_Db_Adapter_Abstract class
        if (! $dbAdapter instanceof Zend_Db_Adapter_Abstract) {
            throw new Zend_Db_Exception("Adapter class '$adapterName' does not extend Zend_Db_Adapter_Abstract");
        }

        return $dbAdapter;
	}
	
	/**
	 * 
	 */
	public static function dbPool ($options = array(), $charset = null)
	{
		// check options
		if (!isset($options['type']) ||
			!isset($options['host']) ||
			!isset($options['user']) ||
			!isset($options['pass']) ||
			!isset($options['name'])) {
			throw new Hush_Db_Exception('Invalid db options');
		}
		
		// default port
		if (!isset($options['port'])) {
			$options['port'] = '3306'; 
		}
		
		// get db link from pool
		$dbPoolKey = $options['type'].':'.$options['host'].':'.$options['port'].':'.$options['name'];
		if (!isset(self::$db_pool[$dbPoolKey])) {
			// create db connection here ...
			self::$db_pool[$dbPoolKey] = self::factory($options['type'], array(
				'host'     => $options['host'],
				'port'     => $options['port'],
				'username' => $options['user'],
				'password' => $options['pass'],
				'dbname'   => $options['name']
			));
			// set connection charset
			if ($charset) {
				self::$db_pool[$dbPoolKey]->query('set names ' . $charset);
			}
		}
		
		return self::$db_pool[$dbPoolKey];
	}
}
