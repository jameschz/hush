<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Page
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @see Hush_Exception
 */
require_once 'Hush/Exception.php';

/**
 * @see Hush_Session
 */
require_once 'Hush/Session.php';

/**
 * @see Hush_Debug
 */
require_once 'Hush/Debug.php';

/**
 * @see Hush_View
 */
require_once 'Hush/View.php';

/**
 * @see Hush_Util
 */
require_once 'Hush/Util.php';

/**
 * @package Hush_Page
 */
class Hush_Page
{
	/**
	 * @staticvar bool
	 */
	public static $autoLoad = true;
	
	/**
	 * @access protected
	 * @var string
	 */
	protected $tplDir;
	
	/**
	 * @access protected
	 * @var string
	 */
	protected $pageName;
	
	/**
	 * @access protected
	 * @var string
	 */
	protected $tplName;
	
	/**
	 * @access protected
	 * @var Hush_View
	 */
	protected $view;	// Hush_View
	
	/**
	 * @access private
	 * @var Hush_Debug
	 */
	private $_debug;	// Hush_Debug
	
	/**
	 * Construct
	 * Main page process
	 */
	public function __construct() 
	{
		if (self::$autoLoad === true) {
			$this->__prepare();
			$this->__process();
			$this->__display();
		}
	}
	
	/**
	 * Call action method
	 * @throws Hush_Page_Exception
	 */
	public function __call ($name, $arguments) 
	{
		if (!method_exists($this, $name)) {
			require_once 'Hush/Page/Exception.php';
			throw new Hush_Page_Exception('Could not find action method \'' . $name . '\' in page class \'' . get_class($this) . '\'');
		}
	}
	
	/**
	 * Close autoload process
	 * You should call each page process manually
	 * @static
	 * @return unknown
	 */
	public static function closeAutoLoad () 
	{
		self::$autoLoad = false;
	}
	
	/**
	 * Set page's debug level
	 * @param int $level
	 * @return unknown
	 */
	public function setDebugLevel ($level = Hush_Debug::DEBUG)
	{
		if ($this->_debug) {
			$this->_debug->setDebugLevel($level);
		}
	}
	
	/**
	 * Set template dir
	 * @param string $dir
	 * @throws Hush_Page_Exception
	 * @return Hush_Page
	 */
	public function setTemplateDir ($dir) 
	{
		if (!is_dir($dir)) {
			require_once 'Hush/Page/Exception.php';
			throw new Hush_Page_Exception('Could not find tpl directory \'' . $dir . '\'');
		}
		$this->tplDir = $dir; // for tpl base dir
		return $this;
	}
	
	/**
	 * Return template dir
	 * @return string
	 */
	public function getTemplateDir () 
	{
		return $this->tplDir;
	}
	
	/**
	 * Set page name (in subpage class)
	 * @access protected
	 * @param string $name
	 * @return Hush_Page
	 */
	public function setPageName ($name) 
	{
		$this->view->_page = $this->pageName = $name;
		return $this;
	}
	
	/**
	 * Return page name
	 * @access protected
	 * @return string
	 */
	public function getPageName () 
	{
		return $this->pageName;
	}
	
	/**
	 * Set template name (in subpage class)
	 * @access protected
	 * @param string $name
	 * @return Hush_Page
	 */
	public function setTemplate ($name) 
	{
		$this->tplName = $name;
		return $this;
	}
	
	/**
	 * Return template name
	 * @access protected
	 * @return string
	 */
	public function getTemplate () 
	{
		return $this->tplName;
	}
	
	/**
	 * Return template name
	 * @access protected
	 * @return string
	 */
	public function getView () 
	{
		return $this->view;
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
	 * Used by subpages for fetching all cookies
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return mixed 
	 */
	protected function cookie ($key = '', $value = null) 
	{
		return Hush_Util::cookie($key, $value);
	}
	
	/**
	 * Used by subpages for fetching all sessions
	 * @access protected
	 * @param string $key
	 * @param mixed $value
	 * @return mixed 
	 */
	protected function session ($key = '', $value = null) 
	{
		return Hush_Util::session($key, $value);
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
			// get label string
			$reflection = new ReflectionClass('Hush_Debug');
			$levels = $reflection->getConstants();
			$label = '[' . array_search($level, $levels) . '] ' . $label;
			// print debug info
			$this->_debug->debug($msg, $label, $level);
		}	
	}
	
	/**
	 * Do some initialization for page process
	 * Could be called by some class which need do page process manually
	 * @see Hush_App_Dispatcher
	 * @return unknown
	 */
	public function __prepare () 
	{
		// page execute time
		if (Hush_Debug::showDebug('time')) {
			$this->start_time = microtime(true);
		}
		
		// insert callback method
		$this->__before_prepare();
		
		// set page debug object
		$this->_debug = Hush_Debug::getInstance();
		$this->_debug->addWriter(new Hush_Debug_Writer_Html());
		
		// close debug infos in www
		if (!strcmp(__ENV, 'www')) {
			$this->_debug->setDebugLevel(Hush_Debug::INFO);
		}
		
		// set page tpl object
		$view_engine = defined('__TPL_ENGINE') ? __TPL_ENGINE : 'Smarty';
		$this->view = Hush_View::getInstance($view_engine, array(
			'template_dir'	=> $this->tplDir . DIRECTORY_SEPARATOR . 'template',
			'compile_dir'	=> $this->tplDir . DIRECTORY_SEPARATOR . 'template_c',
			'config_dir'	=> $this->tplDir . DIRECTORY_SEPARATOR . 'config',
			'cache_dir'		=> $this->tplDir . DIRECTORY_SEPARATOR . 'cache',
		));
		
		// insert callback method
		$this->__before_process();
	}
	
	/**
	 * Assign prepared data into template and display
	 * Could be called by some class which need do page process manually
	 * @see Hush_App_Dispatcher
	 * @param string $tpl_name Passed template name
	 * @return unknown
	 */
	public function __display ($tpl_name = null) 
	{
		// display setted template
		if ($this->getTemplate()) {
			$tpl_name = $this->getTemplate();
		}
		
		// display passed template
		// TODO : Smarty 3 bug in caching
		if ($tpl_name && $this->view->templateExists($tpl_name)) {
			$this->view->display($tpl_name);
		}
		
		// page execute time
		if (Hush_Debug::showDebug('time')) {
			$this->end_time = microtime(true);
			$this->debug(__TPL_ENGINE . '_' . $this->view->getVersion(), '<span style="color:red">Template Engine Version >>></span>', Hush_Debug::INFO);
			$this->debug($this->end_time - $this->start_time, '<span style="color:red">Page Execute Time >>></span>', Hush_Debug::INFO);
			$this->debug($this->view->isCached($tpl_name), '<span style="color:red">Page Cached >>></span>', Hush_Debug::INFO);
		}
		
		// print debug msg
		$this->_debug->write();
	}
	
	//////////////////////////////////////////////////////////////////////////////////////////////////////////////////
	// implemented in sub class.
	
	/**
	 * Could be overload by subclasses
	 * For doing something before page preparation (page's objects initialization)
	 * @return unknown
	 */
	protected function __before_prepare () {}
	
	/**
	 * Could be overload by subclasses
	 * For doing something before page main process
	 * @return unknown
	 */
	protected function __before_process () {}
	
	/**
	 * Could be overload by subclasses
	 * For implement the main process of the subpages
	 * @return unknown
	 */
	protected function __process () {}
}
