<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Cli
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see 
 */
require_once 'Hush/Cli/Exception.php';

/**
 * @abstract
 * @package Hush_Cli
 */
abstract class Hush_Cli
{
	/**
	 * @var array
	 */
	protected $_argv = array();
	
	/**
	 * @var string
	 */
	protected $_className = null;
	
	/**
	 * @var string
	 */
	protected $_methodName = null;
	
	/**
	 * @var string
	 */
	protected $_defaultClassName = 'help';
	
	/**
	 * @var string
	 */
	protected $_defaultMethodName = 'help';
	
	/**
	 * Constructor
	 */
	public function __construct ()
	{
		$this->__init();
	}
	
	/**
	 * Get class and method from input
	 * 
	 * @return void
	 */
	public function __init ()
	{
		global $argv;
		
		$this->_argv = $argv;
		$this->_className = isset($this->_argv[1]) ? $this->_argv[1] : $this->_defaultClassName;
		$this->_methodName = isset($this->_argv[2]) ? $this->_argv[2] : $this->_defaultMethodName;
		
		if (!$this->_className || !$this->_methodName) {
			throw new Hush_Cli_Exception('Invalid cli parameter');
		}
	}
	
	/**
	 * Start call specific class and method
	 * 
	 * @return void
	 */
	public function start ()
	{
		try {
			$methodName = $this->_methodName . 'Action';
			$methodParams = isset($this->_argv[3]) ? array_slice($this->_argv, 3) : array();
			if (!method_exists($this, $methodName)) {
				throw new Hush_Cli_Exception('Error : Can not found input command');
			} else {
				call_user_func_array(array($this, $methodName), $methodParams);
			}
		} catch (Exception $e) {
			throw new Hush_Cli_Exception($e->getMessage());
		}
	}
	
	/**
	 * Execute command (Support Multi-Process)
	 * 
	 * @param string $command Command string
	 * @param int $processNum Multi-Process number
	 */
	public function exec ($command, $processNum = 1)
	{
		$resultStr = "";
		$processList = array();
		for ($i = 0; $i < $processNum; $i++) {
			$processList[] = popen($command, 'r');
		}
		foreach ($processList as $pid => $pfd) {
			$resultStr .= ">>> PROCESS [$pid]\n";
			while (!feof($pfd)) {
				$resultStr .= fgets($pfd);
			}
			pclose($pfd);
		}
		return $resultStr;
	}
	
	/**
	 * Running interface
	 */
	abstract public function run ();
}