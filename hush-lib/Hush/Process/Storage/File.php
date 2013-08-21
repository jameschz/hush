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
class Hush_Process_Storage_File extends Hush_Process_Storage 
{
	/**
	 * @staticvar int
	 */
	public static $dbPath = '/tmp';

	/**
	 * @staticvar int
	 */
	public static $nullVal = 0;

	/**
	 * @var string 
	 */
	public $name = __CLASS__;

	/**
	 * @var string 
	 */
	public $file = '';

	/**
	 * Construct
	 * @param array $config Settings array
	 */
	public function __construct($config = array ()) 
	{
		// check runtime system
		if (!extension_loaded("dba")) {
			throw new Hush_Process_Exception("You need to open dba extension");
			exit;
		}

		// check config array
		if (!$config['name']) {
			throw new Hush_Process_Exception("Config array must have a key named 'name'");
			exit;
		}

		// init storage handler
		$this->name = (string) $config['name']; // must be int

		// init 
		$this->file = self :: $dbPath . DIRECTORY_SEPARATOR . $this->name . '.db';
	}

	/**
	 * Get db resource from static array
	 * @return mixed
	 */
	private function __getdb() 
	{
		$db = trim($this->_fileGetContents($this->file));

		return $db ? unserialize($db) : array ();
	}

	/**
	 * Get db resource from static array
	 * @param mixed data
	 * @return bool
	 */
	private function __setdb($data) 
	{
		return $this->_filePutContents($this->file, serialize($data));
	}

	/**
	 * Return the file content of the given file
	 * @param  string $file File complete path
	 * @return string File content (or false if problem)
	 */
	protected function _fileGetContents($file) 
	{
		$result = false;
		if (!is_file($file)) {
			return false;
		}
		$f = @fopen($file, 'rb');
		if ($f) {
			@flock($f, LOCK_SH);
			$result = stream_get_contents($f);
			@flock($f, LOCK_UN);
			@fclose($f);
		}
		return $result;
	}

	/**
	 * Put the given string into the given file
	 * @param  string $file File complete path
	 * @param  string $string String to put in file
	 * @return boolean true if no problem
	 */
	protected function _filePutContents($file, $string) 
	{
		$result = false;
		$f = @ fopen($file, 'ab+');
		if ($f) {
			@flock($f, LOCK_EX);
			fseek($f, 0);
			ftruncate($f, 0);
			$tmp = @fwrite($f, $string);
			if (!($tmp === FALSE)) {
				$result = true;
			}
			@fclose($f);
		}
		return $result;
	}

	/**
	 * Set data into storage
	 * @param string $k
	 * @param mixed $v
	 * @return bool
	 */
	public function set($k, $v) 
	{
		$key = (string) trim($k);
		$val = $v ? $v : self :: $nullVal;
		$dat = $this->__getdb();
		$dat[$key] = $val;
		return $this->__setdb($dat);
	}

	/**
	 * Get data from storage
	 * @param string $k
	 * @return mixed
	 */
	public function get($k) 
	{
		$key = (string) trim($k);
		$dat = $this->__getdb();
		if ($dat[$key])
			$val = $dat[$key];
		return $val ? $val : self :: $nullVal;
	}

	/**
	 * Delete data from storage
	 * @param string $k
	 * @return bool
	 */
	public function delete($k) 
	{
		$key = (string) trim($k);
		$dat = $this->__getdb();
		unset ($dat[$key]);
		return $this->__setdb($dat);
	}

	/**
	 * Remove all data
	 */
	public function remove() 
	{
		//return @dba_close($this->__db());
	}
}