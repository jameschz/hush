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
 * @see Zend_Loader
 */
require_once 'Zend/Loader.php';

/**
 * @see Zend_Controller_Request_Http
 */
require_once 'Zend/Controller/Request/Http.php';

/**
 * @see Hush_Debug
 */
require_once 'Hush/Debug.php';


/**
 * @package Hush_App
 */
class Hush_App_Dispatcher
{
	/**
	 * @var Zend_Controller_Request_Http
	 */
	private $_request = null;
	
	/**
	 * @var Hush_App_Mapper
	 */
	private $_mapper = null;
	
	/**
	 * @var string pathinfo
	 */
	private $_path = null;
	
	/**
	 * @var array
	 */
	private $_epage = array();
	
	/**
	 * For debug mode
	 * @var bool
	 */
	private $_debug = false;
	
	/**
	 * For page's debug level
	 * @var bool
	 */
	private $_debugLevel = false;
	
	/**
	 * Default class name
	 * @var string
	 */
	public $defaultClassName = 'Index';
	
	/**
	 * Default action name
	 * @var string
	 */
	public $defaultActionName = 'Index';
	
	/**
	 * Default class name suffix
	 * @var string
	 */
	public $defaultClassSuffix = 'Page';
	
	/**
	 * Default action name suffix
	 * @var string
	 */
	public $defaultActionSuffix = 'Action';
	
	/**
	 * If using page view
	 * @var boolean
	 */
	public static $pageViewClass = true;
	
	/**
	 * Construct
	 * @param Hush_App_Mapper $mapper
	 */
	public function __construct ($mapper = null)
	{
		if ($mapper) {
			$this->setMapper($mapper);
		}
		$this->setRequest(new Zend_Controller_Request_Http());
	}
	
	/**
	 * Set debug mode
	 * @param bool $debug
	 */
	public function setDebug ($debug = true)
	{
		$this->_debug = $debug;
	}
	
	/**
	 * Set page's debug level
	 * @param int $level
	 * @return unknown
	 */
	public function setDebugLevel ($level = Hush_Debug::DEBUG)
	{
		$this->_debugLevel = $level;
	}
	
	/**
	 * Set error (404) page
	 * @param string $err_page
	 */
	public function setErrorPage ($err_page)
	{
		$this->_epage = $err_page;
	}
	
	/**
	 * Set request object for dispatcher
	 * @param Zend_Controller_Request_Http $request
	 */
	public function setRequest ($request)
	{
		if (!($request instanceof Zend_Controller_Request_Abstract)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Please specify a valid request for dispatcher');
		}
		$this->_request = $request;
	}
	
	/**
	 * Get request instance
	 * @return Zend_Controller_Request_Http
	 */
	public function getRequest ()
	{
		return $this->_request;
	}
	
	/**
	 * Set mapper class for url router mapping
	 * @param Hush_App_Mapper $mapper
	 */
	public function setMapper ($mapper)
	{
		if (!($mapper instanceof Hush_App_Mapper)) {
			require_once 'Hush/App/Exception.php';
			throw new Hush_App_Exception('Please specify a valid mapper for dispatcher');
		}
		$this->_mapper = $mapper;
	}
	
	/**
	 * Get mapper instance
	 * @return Hush_App_Mapper
	 */
	public function getMapper ()
	{
		return $this->_mapper;
	}
	
	/**
	 * Mapped page class name
	 * @access protected
	 * @param string $map_path
	 * @return string
	 */
	protected function getMapPageClass ($map_path)
	{
		$paths = $this->_parseMapPath($map_path);
		$className = array_shift($paths);
		$className = $className ? $className : $this->defaultClassName;
		return ucfirst($className);
	}
	
	/**
	 * Mapped page action method name
	 * @access protected
	 * @param string $map_path
	 * @return string
	 */
	protected function getMapPageAction ($map_path)
	{
		$paths = $this->_parseMapPath($map_path);
		array_shift($paths); // escape page name
		$actionName = array_shift($paths);
		$actionName = $actionName ? $actionName : $this->defaultActionName;
		return $actionName;
	}
	
	/**
	 * Default page class name
	 * @access protected
	 * @return string
	 */
	protected function getDefaultPageClass ()
	{
		$paths = $this->_parsePath($this->_path);
		$className = array_shift($paths);
		$className = $className ? $className : $this->defaultClassName;
		return ucfirst($className) . $this->defaultClassSuffix;
	}
	
	/**
	 * Default page action method name
	 * @access protected
	 * @return string
	 */
	protected function getDefaultPageAction ()
	{
		$paths = $this->_parsePath($this->_path);
		array_shift($paths); // escape page name
		$actionName = array_shift($paths);
		$actionName = $actionName ? $actionName : $this->defaultActionName;
		return $actionName . $this->defaultActionSuffix;
	}
	
	/**
	 * Get action args from request url
	 * @return array
	 */
	protected function getActionArgs ()
	{
		$actionArgs = array(); // for debug
		$paths = $this->_parsePath($this->_path);
		array_shift($paths); // escape page name
		array_shift($paths); // escape action name
		foreach (array_chunk($paths, 2) as $group) {
			$k = isset($group[0]) ? trim($group[0]) : '';
			$v = isset($group[1]) ? trim($group[1]) : '';
			if (strlen($k) > 0) { // key can not be empty
				$_REQUEST[$k] = $_GET[$k] = $actionArgs[$k] = $v;
			}
		}
		return $actionArgs;
	}
	
	/**
	 * Find specific template file name
	 * @access protected
	 * @param string $className
	 * @param string $actionName
	 * @return string or bool
	 */
	protected function getTemplateName ($className, $actionName)
	{
		if ($className && $actionName) {
			$className = str_replace($this->defaultClassSuffix, '', $className);
			$actionName = str_replace($this->defaultActionSuffix, '', $actionName);
			return strtolower($className . DIRECTORY_SEPARATOR . $actionName . '.tpl');
		}
		return false;
	}
	
	/**
	 * Format path url
	 * @access private
	 * @return unknown
	 */
	private function _formatPath ()
	{
		// get raw url path
		if (!$this->_path) {
			$this->_path = $this->_request->getPathInfo();
			$this->_path = preg_replace('/\/+/i', '/', $this->_path);
		}
	}
	

	
	/**
	 * Parse common path by specific separator
	 * @return array
	 */
	private function _parsePath ($path) 
	{
		return array_filter(explode('/', $path)); // strip empty value
	}
	
	/**
	 * Parse mapper path by specific separator
	 * @return array
	 */
	private function _parseMapPath ($path)
	{
		return array_filter(explode('::', $path)); // strip empty value
	}
	
	/**
	 * Print debug information
	 * @param array $debugInfo
	 * @param object $e
	 */
	private function _printDebugInfo ($debugInfo, $e)
	{
		echo '<b>Dispatch Debug Info >>></b>' . "<br/>\n" . "<br/>\n";
		echo 'Class Name : ' . $debugInfo['className'] . "<br/>\n";
		echo 'Action Name : ' . $debugInfo['actionName'] . "<br/>\n";
		echo 'Action Args : ' . json_encode($debugInfo['actionArgs']) . "<br/>\n";
		echo 'Template Name : ' . $debugInfo['templateName'] . "<br/>\n" . "<br/>\n";
		echo '<b>Dispatch Exception Info >>></b>' . "<br/>\n";
		Hush_Util::trace($e);
		exit;
	}
	
	/**
	 * Main dispatch process
	 * @param array $app_dir
	 * @param array $tpl_dir
	 * @return unknown
	 */
	public function dispatch ($app_dir, $tpl_dir)
	{
		// app dispatch time
		if (Hush_Debug::showDebug('time')) {
			$this->start_time = microtime(true);
		}
		
		$this->_formatPath();
		
		$mapper_class = null;
		$mapper = $this->getMapper();
		
		/* MAIN PROCESS
		 * Parse mapping file
		 * Get mapped class
		 */
		if ($mapper) {
			
			// prepare mapping 
			$page_map = $mapper->getPageMap();
			
			// do mapping loop
			$path_raw = $this->_path;
			foreach ((array) $page_map as $pattern => $class) {
				// handle REWRITE rules
				if (strpos($class, '/') === 0) {
					$pattern = preg_quote($pattern, '/');
					$pattern = str_replace('\*', '(.*?)', $pattern);
					$replacement = str_replace('*', '$1', $class); // not preg format
					$path_raw = preg_replace('/^' .$pattern . '$/i', $replacement, $path_raw);
					continue;
				}
				// handle NOT REGEXP rules
				if (strpos($pattern, '*') === false) {
					if (!strcasecmp($path_raw, $pattern) || 
						!strcasecmp($this->_path, $pattern)) {
						$mapper_class = $class;
						break;
					}
					continue;
				}
				// handle REGEXP rules
				if (true) {
					$pattern = preg_quote($pattern, '/');
					$pattern = str_replace('\*', '(.*?)', $pattern);
					if (preg_match('/^' .$pattern . '$/i', $path_raw, $path_args)) {
						$mapper_class = $class;
						// fill params
						foreach ($path_args as $k => $v) {
							$_REQUEST['$'.$k] = $v;
						}
						break;
					}
					continue;
				}
			}
		}
		
		/* MAIN PROCESS
		 * Get class & action name
		 */
		if ($mapper_class) {
			// get mapped class
			$className = $this->getMapPageClass($mapper_class);
			// get action from mapping rule
			if (strpos($mapper_class, '*') === false) {
				$actionName = $this->getMapPageAction($mapper_class);
			// get action from url path
			} else {
				$actionName = $this->getDefaultPageAction();
			}
		}
		else {
			// get default class & action from url
			$className = $this->getDefaultPageClass();
			$actionName = $this->getDefaultPageAction();
		}
		
		/* MAIN PROCESS
		 * Get action args
		 */
		$actionArgs = $this->getActionArgs();
		
		/* MAIN PROCESS
		 * Get template name
		 */
		$templateName = $this->getTemplateName($className, $actionName);
		
		// app dispatch time
		if (Hush_Debug::showDebug('time')) {
			$this->end_time = microtime(true);
			$debug = Hush_Debug::getInstance();
			$debug->setWriter(new Hush_Debug_Writer_Html());
			$debug->debug($this->end_time - $this->start_time, '<span style="color:red">Hush App Dispatch Time >>></span>', Hush_Debug::INFO);
		}
		
		/* MAIN PROCESS
		 * find page by url
		 */
		try {
			
			// load page class
			@Zend_Loader::loadClass($className, $app_dir); // debug should be closed
			if (!class_exists($className)) {
				require_once 'Hush/App/Exception.php';
				throw new Hush_App_Exception('Can not find definition for class \'' . $className . '\'');
			}
			
		} catch (Exception $e) {
			
			require_once 'Hush/Util.php';
			Hush_Util::HTTPStatus(404);
			if (!$this->_debug) {
				if (file_exists($this->_epage[404])) {
					include_once $this->_epage[404];
					exit;
				}
			} else {
				$this->_printDebugInfo(array(
					'className'		=> $className,
					'actionName'	=> $actionName,
					'actionArgs'	=> $actionArgs,
					'templateName'	=> templateName,
				), $e);
			}
			
		}
		
		/* MAIN PROCESS
		 * display page by url
		*/
		try {
			
			// close auto-load for page view class
			if (self::$pageViewClass) {
				require_once 'Hush/Page.php';
				Hush_Page::closeAutoLoad(); // close page autoload mechanism
			}
			
			// create page
			$page = new $className();
			
			/* USE PAGE VIEW PROCESS
			 * set template for page view class
			 */
			if (self::$pageViewClass) {
				if ($tpl_dir) $page->setTemplateDir($tpl_dir);
				$page->__prepare();
			}
			
			// set page's debug level
			if ($this->_debugLevel) {
				$page->setDebugLevel($this->_debugLevel);
			}
			
			// callback method implemented in page class
			if (method_exists($page, '__init')) {
				$page->__init();
			}
			
			// call page action method
			$page->$actionName($actionArgs);
			
			// callback method implemented in page class
			if (method_exists($page, '__done')) {
				$page->__done();
			}
			
			/* USE PAGE VIEW PROCESS
			 * display template for page view class
			 */
			if (self::$pageViewClass) {
				$page->__display($templateName);
			}
			
		} catch (Exception $e) {
			
			require_once 'Hush/Util.php';
			Hush_Util::HTTPStatus(500);
			if (!$this->_debug) {
				
				if (file_exists($this->_epage[500])) {
					include_once $this->_epage[500];
					exit;
				}
			} else {
				$this->_printDebugInfo(array(
					'className'		=> $className,
					'actionName'	=> $actionName,
					'actionArgs'	=> $actionArgs,
					'templateName'	=> templateName,
				), $e);
			}
			
		}
	}
		
}