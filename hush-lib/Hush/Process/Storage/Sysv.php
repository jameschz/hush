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
 * @see Hush_Process_Exception
 */
require_once 'Hush/Process/Exception.php';

/**
 * @see Hush_Process_Storage
 */
require_once 'Hush/Process/Storage.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @package Hush_Process
 */
class Hush_Process_Storage_Sysv extends Hush_Process_Storage
{
	/**
	 * @staticvar int
	 */
	public static $nullVal = 0;
	
	/**
	 * @var resource 
	 */
	public $db = null;
	
	/**
	 * @staticvar int
	 */
	public static $size = 16777216; // 1024 * 1024 * 16
	
	/**
	 * Construct
	 * @param array $config Settings array
	 */
	public function __construct ($config = array())
	{
		// check runtime system
		if (!extension_loaded("sysvshm")) {
			throw new Hush_Process_Exception("You need to open sysvshm extension");
			exit;
		}
		
		// check config array
		if (!$config['name']) {
			throw new Hush_Process_Exception("Config array must have a key named 'name'");
			exit;
		}
		
		// init storage handler
		$sysv_id = (int) $config['name']; // must be int
		if(!$this->db = shm_attach($sysv_id, self::$size)) {
			throw new Hush_Process_Exception("Storage initialization failed");
			exit;
		}
	}
	
	/**
	 * Get string or key's hash code
	 * @param string $s
	 * @return int
	 */
	private function __hashcode ($s)
	{
		$base = ftok(__FILE__, 'r');
		$code = $base + Hush_Util::str_hash($s);
		return $code ? $code : $base;
	}
	
	/**
	 * Set data into storage
	 * @param string $k
	 * @param mixed $v
	 * @return bool
	 */
	public function set ($k, $v)
	{
		$key = $this->__hashcode($k);
		$val = $v ? $v : self::$nullVal;
		return @shm_put_var($this->db, $key, $val);
	}
	
	/**
	 * Get data from storage
	 * @param string $k
	 * @return mixed
	 */
	public function get ($k)
	{
		$key = $this->__hashcode($k);
		$val = @shm_get_var($this->db, $key);
		return $val ? $val : self::$nullVal;
	}
	
	/**
	 * Delete data from storage
	 * @param string $k
	 * @return bool
	 */
	public function delete ($k)
	{
		$key = $this->__hashcode($k);
		return @shm_remove_var($this->db, $key);
	}
	
	/**
	 * Remove all data
	 */
	public function remove ()
	{
		return @shm_remove($this->db);
	}
}
