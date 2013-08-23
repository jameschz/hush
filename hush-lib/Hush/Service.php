<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Service
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * Service class do not need page view components
 * Default view engine is smarty
 */
Hush_App::setPageView(false);

/**
 * @see Hush_Debug
 */
require_once 'Hush/Debug.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @package Hush_Service
 */
class Hush_Service
{
	/**
	 * @var Hush_Debug
	 */
	private $_debug;
	
	/**
	 * @var int
	 */
	protected $_rtime;
	
	/**
	 * Construct
	 * Main page process
	 */
	public function __construct() 
	{
		
	}
	
	/**
	 * Call action method
	 * @throws Hush_Page_Exception
	 */
	public function __call ($name, $arguments) 
	{
		if (!method_exists($this, $name)) {
			require_once 'Hush/Service/Exception.php';
			throw new Hush_Service_Exception('Could not find action method \'' . $name . '\' in service class \'' . get_class($this) . '\'');
		}
	}
	
	/**
	 * Used by subpages for fetching all request
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return mixed 
	 */
	protected function param ($key = '', $value = null) 
	{
		return Hush_Util::param($key, $value);
	}
	
	/**
	 * Used by subpages for debug some infomation
	 * @access protected
	 * @param string $msg Message content
	 * @param string $label Message label string
	 * @param int $level Hush_Debug::LEVEL
	 * @return unknown
	 */
	protected function debug ($msg, $label = null, $level = Hush_Debug::DEBUG)
	{
		if ($this->_debug) {
			
//			if (!$label) {
				$reflection = new ReflectionClass('Hush_Debug');
				$levels = $reflection->getConstants();
				$label = '[' . array_search($level, $levels) . '] ' . $label;
//			}
			
			$this->_debug->debug($msg, $label, $level);
		}	
	}
	
	/**
	 * Do some initialization for page process
	 * Could be called by some class which need do page process manually
	 * @see Hush_App_Dispatcher
	 * @return unknown
	 */
	public function __init () 
	{
		// page execute time
		$this->start_time = microtime(true);
		
		// set page debug object
		$this->_debug = Hush_Debug::getInstance();
		$this->_debug->setWriter(new Hush_Debug_Writer_Html());
		
		// close debug infos in www
		if (!strcmp(__ENV, 'www')) {
			$this->_debug->setDebugLevel(Hush_Debug::INFO);
		}
	}
	
	/**
	 * Assign prepared data into template and display
	 * Could be called by some class which need do page process manually
	 * @see Hush_App_Dispatcher
	 * @param string $tpl_name Passed template name
	 * @return unknown
	 */
	public function __done () 
	{		
		// page execute time
		$this->end_time = microtime(true);
		$this->_rtime = $this->end_time - $this->start_time;
		
		// whether display
		if (Hush_Debug::showDebug('time')) {
			$this->debug($this->_rtime, '<span style="color:red">Service Execute Time >>></span>', Hush_Debug::INFO);
		}
		
		// print debug msg
		$this->_debug->write();
	}
	
}
