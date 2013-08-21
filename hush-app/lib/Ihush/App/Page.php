<?php
/**
 * Ihush App
 *
 * @category   Ihush
 * @package    Ihush_App
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Page.php';
require_once 'Hush/Session.php';

/**
 * @package Ihush_App
 */
class Ihush_App_Page extends Hush_Page
{
	/**
	 * @var array
	 */
	protected $errors = array();
	
	/**
	 * Do something before __prepare() method
	 * @see Hush_Page
	 */
	public function __before_prepare ()
	{
		// auto init page tpl path
		if (defined('__TPL_SMARTY_PATH')) {
			$this->setTemplateDir(__TPL_SMARTY_PATH);
		}
	}
	
	/**
	 * Do something before __process() method
	 * @see Hush_Page
	 */
	public function __before_process ()
	{
		// init page system settings
		$this->view->_page	= $this->page	= $_SERVER['REQUEST_URI'];
		$this->view->_refer	= $this->refer	= $_SERVER['HTTP_REFERER'];
		$this->view->_host	= $this->host	= __HTTP_HOST;
		$this->view->_root	= $this->root	= __HTTP_ROOT;
		$this->view->_time	= $this->time	= time();
		$this->view->_rand	= $this->rand	= rand();
	}
	
	/**
	 * Add error message directly
	 * 
	 * @param string $msg Error message string
	 * @return bool
	 */
	public function addErrorMsg ($msg)
	{
		$this->errors[] = $msg;
		
		$this->view->errors = $this->errors;
		
		return $this;
	}
	
	/**
	 * Add error messages into template, and then render it
	 * 
	 * @param string $msg Error message id
	 * @return Ihush_App
	 */
	public function addError ($msg)
	{		
		if (!file_exists(__MSG_INI_FILE)) {
			require_once 'Ihush/App/Exception.php';
			throw new Ihush_App_Exception('Error messages ini file can not be found');
		}
		
		$error_tpl_arr = parse_ini_file(__MSG_INI_FILE);
		$error_tpl_str = isset($error_tpl_arr[$msg]) ? $error_tpl_arr[$msg] : 'undefined';
		
		$error_str = $error_tpl_str; // default error msg
		
		$args = func_get_args();
		@array_shift($args); // remove first parameter (msg id)
		
		// should do replace
		if (sizeof($args) > 0) {
			$replace_old = array();
			$replace_new = array();
			foreach ($args as $id => $arg) {
				$replace_old[] = '{' . $id . '}';
				$replace_new[] = $arg;
			}
			$error_str = str_replace($replace_old, $replace_new, $error_tpl_str);
		}
		
		return $this->addErrorMsg($error_str);
	}
	
	/**
	 * Judge if there is not error for the form
	 * 
	 * @return bool
	 */
	public function noError ()
	{
		return (sizeof($this->errors) > 0) ? false : true;
	}
	
	/**
	 * Rebuild array into smarty options array
	 * 
	 * @return array
	 */
	public function makeOpt ($arr, $k, $v)
	{
		$opt = array();
		foreach ($arr as $row) {
			if (!$row[$k] || !$row[$v]) continue;
			$opt[$row[$k]] = $row[$v];
		}
		return $opt;
	}
	
	/**
	 * Forward page by header redirection
	 * J2EE like method's name :)
	 * 
	 * @param string $url
	 * @return void
	 */
	public function forward ($url = '')
	{
		Hush_Util::headerRedirect($url);
		exit;
	}
	
	/**
	 * Set variables into template, and then render it
	 * ROR like method's name :)
	 * 
	 * @param string $tpl_name
	 * @return Ihush_App
	 */
	public function render ($tpl_name)
	{
		return $this->setTemplate($tpl_name);
	}
}
