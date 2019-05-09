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
 * @see Hush_View
 */
require_once 'Hush/View.php';

/**
 * Smarty base class
 * @see Smarty_3/Smarty.class.php
 */
$smarty_engine = defined('__TPL_LIB_PATH') ? __TPL_LIB_PATH : 'Smarty_3';
require_once $smarty_engine . '/Smarty.class.php';

/**
 * @package Hush_View
 */
class Hush_View_Smarty extends Hush_View implements Zend_View_Interface 
{
	/**
	 * Smarty object
	 * @var Smarty
	 */
	protected static $_engine;
	
	/**
	 * Smarty object
	 * @var Smarty
	 */
	protected $_smarty;
	
	/**
	 * Smarty version
	 * @var string
	 */
	protected $_version;

	/**
	 * Constructor
	 * @param string $tmplPath
	 * @param array $extraParams
	 * @return void
	 */
	public function __construct($configs = array ()) 
	{
		$this->_smarty = $this->getEngine();

		foreach ($configs as $key => $value) {
			$this->_smarty->$key = $value;
		}
		
		// settings for smarty 3 ; adapt with smarty 2
		$this->_smarty->deprecation_notices = false;
		$this->_smarty->allow_php_tag = true;
		$this->_smarty->auto_literal = true;
	}

	/**
	 * Return the template engine object
	 * @return Smarty
	 */
	public function getEngine() 
	{
		if (!self::$_engine) {
			self::$_engine = new Smarty;
		}
		return self::$_engine;
	}
	
	/**
	 * Return the smarty object
	 * @return Smarty
	 */
	public function getSmarty() 
	{
		return $this->_smarty;
	}
	
	/**
	 * Get smarty version
	 * For adopting smarty 2 with smarty 3
	 * @return string
	 */
	public function getVersion ()
	{
		return $this->_smarty->_version;
	}

	/**
	 * Set the path to the templates
	 * @param string $path The directory to set as the path.
	 * @return void
	 */
	public function setScriptPath($path) 
	{
		if (is_readable($path)) {
			$this->_smarty->template_dir = $path;
			return;
		}

		throw new Exception('Invalid path provided');
	}

	/**
	 * Retrieve the current template directory
	 * @return string
	 */
	public function getScriptPaths() 
	{
		return array (
			$this->_smarty->template_dir
		);
	}

	/**
	 * Alias for setScriptPath
	 * @param string $path
	 * @param string $prefix Unused
	 * @return void
	 */
	public function setBasePath($path, $prefix = 'Zend_View') 
	{
		return $this->setScriptPath($path);
	}

	/**
	 * Alias for setScriptPath
	 * @param string $path
	 * @param string $prefix Unused
	 * @return void
	 */
	public function addBasePath($path, $prefix = 'Zend_View') 
	{
		return $this->setScriptPath($path);
	}

	/**
	 * Check if the template does exist
	 * @param string $name
	 * @return boolean
	 */
	public function templateExists ($name)
	{
		return $this->__autocall('template_exists', array($name));
	}

	/**
	 * Assign a variable to the template
	 * @param string $key The variable name.
	 * @param mixed $val The variable value.
	 * @return void
	 */
	public function __set($key, $val) 
	{
		$this->_smarty->assign($key, $val);
	}

	/**
	 * Retrieve an assigned variable
	 * @param string $key The variable name.
	 * @return mixed The variable value.
	 */
	public function __get($key) 
	{
// 		return $this->_smarty->get_template_vars($key);
		return $this->__autocall('get_template_vars', array($key));
	}

	/**
	 * Allows testing with empty() and isset() to work
	 * @param string $key
	 * @return boolean
	 */
	public function __isset($key) 
	{
// 		return (null !== $this->_smarty->get_template_vars($key));
		return (null !== $this->__autocall('get_template_vars', array($key)));
	}

	/**
	 * Allows unset() on object properties to work
	 * @param string $key
	 * @return void
	 */
	public function __unset($key) 
	{
// 		$this->_smarty->clear_assign($key);
		$this->__autocall('clear_assign', array($key));
	}

	/**
	 * Assign variables to the template
	 * Allows setting a specific key to the specified value, OR passing an array
	 * of key => value pairs to set en masse.
	 * @see __set()
	 * @param string|array $spec The assignment strategy to use (key or array of key
	 * => value pairs)
	 * @param mixed $value (Optional) If assigning a named variable, use this
	 * as the value.
	 * @return void
	 */
	public function assign($spec, $value = null) 
	{
		if (is_array($spec)) {
			$this->_smarty->assign($spec);
			return;
		}

		$this->_smarty->assign($spec, $value);
	}

	/**
	 * Open smarty cache mechanism
	 * @param int $expire Cache page expire time (microseconds) default is 3600
	 * @return void
	 */
	public function setCache ($caching = true, $expire = 3600)
	{
		if ($caching) {
			$this->_smarty->caching = $caching;
			$this->_smarty->cache_lifetime = $expire;
		}
	}

	/**
	 * Check is page cached
	 * @return bool
	 */
	public function isCached ($template)
	{
		return $this->__autocall('is_cached', array($template));
	}

	/**
	 * Clear all assigned variables
	 * Clears all variables assigned to Zend_View either via {@link assign()} or
	 * property overloading ({@link __get()}/{@link __set()}).
	 * @return void
	 */
	public function clearVars() 
	{
// 		$this->_smarty->clear_all_assign();
		$this->__autocall('clear_all_assign');
	}
	
	/**
	 * Clear all cached files
	 * @return void
	 */
	public function clearAllCache() 
	{
		return $this->__autocall('clear_all_cache');
	}
	
	/**
	 * Clear all compiled templates
	 * @return void
	 */
	public function clearTemplates() 
	{
		// adapt with smarty 2
		if (method_exists($this->_smarty, 'clear_compiled_tpl')) {
			return $this->_smarty->clear_compiled_tpl();
		}
		
		// adapt with smarty 3
		return $this->_smarty->clear_compiled_template();
	}
	
	/**
	 * Processes a template and display.
	 * @param string $name The template to process.
	 * @return void
	 */
	public function display($name) 
	{
		if (function_exists('smarty_output_filter')) {
			$this->_smarty->registerFilter('output', 'smarty_output_filter');
		}
		return $this->_smarty->display($name);
	}
	
	/**
	 * Processes a template and returns the output.
	 * @param string $name The template to process.
	 * @return string The output.
	 */
	public function render($name) 
	{
		return $this->_smarty->fetch($name);
	}
	
	/**
	 * Invoke methods adaptly with smarty2 and smarty3
	 * Auto convert foo_bar_baz to fooBarBaz style names
	 * @param string $method_name Smarty method name
	 * @param array $method_args Smarty method args
	 * @return mixed
	 */
	protected function __autocall($method_name, $method_args = array())
	{
		// adapt with smarty 2
		if (method_exists($this->_smarty, $method_name)) {
			return call_user_func_array(array($this->_smarty, $method_name), $method_args);
		}
		
		// adapt with smarty 3
		$name_parts = explode('_', $method_name);
		foreach ($name_parts as $idx => $part) {
			if ($idx == 0) $name_parts[$idx] = strtolower($part);
			else $name_parts[$idx] = ucfirst($part);
		}
		$method_name = implode('',$name_parts);
		
		if(!method_exists($this->_smarty, $method_name)) {
			throw new Exception("unknown smarty method '$method_name'");
			return false;
		}
		
		return call_user_func_array(array($this->_smarty, $method_name), $method_args);			
	}
}