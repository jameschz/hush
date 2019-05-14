<?php
/**
 * App App
 *
 * @category   App
 * @package    App_App
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Page.php';
require_once 'Core/Util.php';

/**
 * @package App_App
 */
class App_App_Page extends Hush_Page
{
	/**
	 * @var array
	 */
	protected $errors = array();
	
	/**
	 * Dao object
	 */
	protected $dao = null;
	
	/**
	 * Service object
	 */
	protected $service = null;
	
	/**
	 * Get dao instance
	 */
	public function __init_dao() {
	    if (!$this->dao) {
	        require_once 'App/Dao.php';
	        $this->dao = new App_Dao();
	    }
	    return $this->dao;
	}
	
	/**
	 * Get dao instance
	 */
	public function __init_service() {
	    if (!$this->service) {
	        require_once 'App/Service.php';
	        $this->service = new App_Service();
	    }
	    return $this->service;
	}
	
	/**
	 * Do something before __prepare() method
	 * @see Hush_Page
	 */
	public function __before_prepare ()
	{
		// auto init page tpl path
		$this->setTemplateDir(__TPL_BASE_PATH, __TPL_COMPILE_PATH);
	}
	
	/**
	 * Do something before __process() method
	 * @see Hush_Page
	 */
	public function __before_process ()
	{
		// init page system settings
		$this->view->_page		= $this->page		= $_SERVER['REQUEST_URI'];
		$this->view->_puid		= $this->puid		= Core_Util::md5($this->page);
		$this->view->_refer		= $this->refer		= $_SERVER['HTTP_REFERER'];
		$this->view->_host		= $this->host		= __HTTP_HOST;
		$this->view->_root		= $this->root		= __HTTP_ROOT;
		$this->view->_time		= $this->time		= time();
		$this->view->_rand		= $this->rand		= rand();
		$this->view->_path		= $this->path		= Core_Util::str_parse_uri();
		$this->view->_host_s	= $this->host_s		= cfg('app.host.s');
		$this->view->_host_c	= $this->host_c		= cfg('app.cdn.url');
		$this->view->_host_u	= $this->host_u		= cfg('app.upload.host');
		$this->view->_dtime		= $this->dtime		= time();
		$this->view->_app		= $this->app		= __APP;
		$this->view->_env		= $this->env		= __HUSH_ENV;
		$this->view->_ver		= $this->ver		= cfg('version');
	}
	
	/**
	 * Get post data
	 * @param array $forms
	 * @param array $fields
	 * @return multitype:unknown
	 */
	public function getPostData ($posts, $fields = array())
	{
		$data = array();
		if (is_array($posts)) {
			// default validate
			$validate_default = array(
				'notempty' => true,
			);
			// get form's data 
			foreach ($posts as $name) {
				$value = $this->param($name);
				// check field
				if ($fields) {
					$field_name = isset($fields[$name]['name']) ? $fields[$name]['name'] : '';
				} else {
					$field_name = $this->getField('field.'.$name);
				}
				// check validate
				$validate = isset($fields[$name]['validate']) ? $fields[$name]['validate'] : array();
				$validate = array_merge($validate_default, $validate);
				if ($validate['notempty'] && Core_Util::str_test_empty($value)) {
					$this->addError('common.err.notempty', $field_name);
				}
				if ($validate['isnumber'] && !Core_Util::str_test_number($value)) {
					$this->addError('common.err.badformat', $field_name);
				}
				if ($validate['isphone'] && !Core_Util::str_test_phone($value)) {
					$this->addError('common.err.badformat', $field_name);
				}
				if ($validate['isurl'] && !Core_Util::str_test_url($value)) {
					$this->addError('common.err.badformat', $field_name);
				}
				// get data if no error
				if ($this->noError()) {
					$data[$name] = $value;
				}
			}
		}
		return $data;
	}
	
	/**
	 * Get form data
	 * @param array $forms
	 * @param array $fields
	 * @return multitype:unknown
	 */
	public function getFormData ($forms = array())
	{
		return $this->getPostData(array_keys($forms), $forms);
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
	 * @return App_App
	 */
	public function addError ($msg)
	{
	    $error_str = call_user_func_array(array($this, 'getField'), func_get_args());
		
		$error_str = ($error_str != 'undefined') ? $error_str : $msg;
		
		return $this->addErrorMsg($error_str);
	}
	
	/**
	 * Add message directly
	 *
	 * @param string $msg Message string
	 * @return bool
	 */
	public function addOkMsg ($msg)
	{
		$this->msgs[] = $msg;
		
		$this->view->msgs = $this->msgs;
		
		return $this;
	}
	
	/**
	 * Add message directly
	 *
	 * @param string $msg Message string
	 * @return bool
	 */
	public function addOk ($msg)
	{
	    $ok_str = call_user_func_array(array($this, 'getField'), func_get_args());
		
		return $this->addOkMsg($ok_str);
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
	
	public function getField ()
	{
		// get error array
		if (!$this->error_arr) {
			if (!file_exists(__MSG_INI_FILE)) {
				require_once 'App/App/Exception.php';
				throw new App_App_Exception('Error messages ini file can not be found');
			}
			$this->error_arr = (array) parse_ini_file(__MSG_INI_FILE);
		}
		
		// default error msg
		$args = func_get_args();
		$msg = array_shift($args); 
		$this->error_str = isset($this->error_arr[$msg]) ? $this->error_arr[$msg] : 'undefined';
		
		// should do replace
		if (sizeof($args) > 0) {
			$replace_old = array();
			$replace_new = array();
			foreach ($args as $id => $arg) {
				$replace_old[] = '{' . $id . '}';
				$replace_new[] = $arg;
			}
			$this->error_str = str_replace($replace_old, $replace_new, $this->error_str);
		}
		
		return $this->error_str;
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
	 * @return App_App
	 */
	public function render ($tpl_name)
	{
		return $this->setTemplate($tpl_name);
	}
	
	public function fetch ($tpl_name)
	{
		return $this->getView()->render($tpl_name);
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// 以下添加基础页面类的通用方法
	
}
