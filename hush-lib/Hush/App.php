<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_App
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_App_Dispatcher
 */
require_once 'Hush/App/Dispatcher.php';

/**
 * @see Hush_App_Mapper
 */
require_once 'Hush/App/Mapper.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @package Hush_App
 */
class Hush_App
{
	/**
	 * App class dirs
	 * @var array
	 */
	private $_dirs = array();
	
	/**
	 * App mappings
	 * @var array
	 */
	private $_maps = array();
	
	/**
	 * Template dir
	 * @var string
	 */
	private $_tpls = '';
	
	/**
	 * Error page
	 * @var string
	 */
	private $_epage = '';
	
	/**
	 * Debug mode
	 * @var bool
	 */
	private $_debug = false;
	
	/**
	 * Page's debug level
	 * @var bool
	 */
	private $_debugLevel = false;
	
	/**
	 * Set debug mode for display app's dispatch infomation
	 * @param bool $debug
	 * @return Hush_App
	 */
	public function setDebug ($debug = true)
	{
		$this->_debug = $debug;
		return $this;
	}
	
	/**
	 * Set debug level for display app's page
	 * @param int $level
	 * @return Hush_App
	 */
	public function setDebugLevel ($level = Hush_Debug::DEBUG)
	{
		$this->_debugLevel = $level;
		return $this;
	}
	
	/**
	 * Set App's error page (404 page)
	 * @param string $err_page (error page url)
	 * @return Hush_App
	 */
	public function setErrorPage ($err_page)
	{
		$this->_epage = $err_page;
		return $this;
	}
	
	/**
	 * Add App's classes dirs for dispatch
	 * @param string $dir
	 * @throws Hush_App_Exception
	 * @return Hush_App
	 */
	public function addAppDir ($dir) 
	{
		if (!is_dir($dir)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Could not found app directory \'' . $dir . '\'');
		}
		$this->_dirs[] = $dir;
		return $this;
	}
	
	/**
	 * Get App's classes dirs
	 * @return array
	 */
	public function getAppDirs () 
	{
		return $this->_dirs;
	}
	
	/**
	 * Add App's router mapping files for dispatch
	 * @param string $map
	 * @throws Hush_App_Exception
	 * @return Hush_App
	 */
	public function addMapFile ($map) 
	{
		if (!is_file($map)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Could not found map file \'' . $map . '\'');
		}
		$this->_maps[] = $map;
		return $this;
	}
	
	/**
	 * Get App's mapping files
	 * @return array
	 */
	public function getMapFiles () 
	{
		return $this->_maps;
	}
	
	/**
	 * Set App's template dir
	 * @param string $dir
	 * @throws Hush_App_Exception
	 * @return Hush_App
	 */
	public function setTplDir ($dir) 
	{
		if (!is_dir($dir)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Could not found template directory \'' . $dir . '\'');
		}
		$this->_tpls = $dir;
		return $this;
	}
	
	/**
	 * Get App's template dirs
	 * @return array
	 */
	public function getTplDir () 
	{
		return $this->_tpls;
	}
	
	/**
	 * stripMagicQuotes if magic_quotes_gpc is On
	 * @return Hush_App
	 */
	public function closeMagicQuotes ()
	{
		global $_GET, $_POST, $_COOKIE, $_REQUEST;
		
		$_GET = $this->_stripMagicQuotes($_GET);
		$_POST = $this->_stripMagicQuotes($_POST);
		$_COOKIE = $this->_stripMagicQuotes($_COOKIE);
		$_REQUEST = $this->_stripMagicQuotes($_REQUEST);
		
		return $this;
	}
	
	/**
	 * callback function for closeMagicQuotes
	 */
	private function _stripMagicQuotes (&$value)
	{
		$value = (is_array($value)) 
			? array_map(array($this, '_stripMagicQuotes'), $value) 
			: stripslashes($value);
		
		return $value;
	}
	
	/**
	 * Start main router and dispatch process for App
	 * @see Hush_App_Dispatcher
	 * @param array $vars Dispatcher vars
	 * @throws Hush_App_Exception
	 * @return unknown
	 */
	public function run ($vars = array())
	{
		if (!$this->getAppDirs()) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Please specify app directory first');
		}
		
		$dispatcher = new Hush_App_Dispatcher();
		
		// if open debug
		if ($this->_debug) {
			$dispatcher->setDebug(true);
		}
		
		// if set debug level
		if ($this->_debugLevel) {
			$dispatcher->setDebugLevel($this->_debugLevel);
		}
		
		// if set error page
		if ($this->_epage) {
			$dispatcher->setErrorPage($this->_epage);
		}
		
		// add page mappings if needed
		if ($this->getMapFiles()) {
			$mapper = new Hush_App_Mapper($this->getMapFiles());
			$dispatcher->setMapper($mapper);
		}
		
		// parse and set dispatch vars
		foreach ((array) $vars as $key => $var) {
			$dispatcher->$key = $var;
		}
		
		// dispatch request to pages' actions
		$dispatcher->dispatch($this->getAppDirs(), $this->getTplDir());
	}
	
	/**
	 * Set if using page view (default is smarty)
	 * @param boolean $pview
	 * @return Hush_App
	 */
	public static function setPageView ($pageViewClass = true)
	{
		Hush_App_Dispatcher::$pageViewClass = $pageViewClass;
		return $this;
	}
	
	/**
	 * Start session manually
	 * @return Hush_App
	 */
	public static function startSession ()
	{
		static $started = false;
		
		if (!$started) {
			session_start();
			$started = true;
		}
		
		return $this;
	}
}