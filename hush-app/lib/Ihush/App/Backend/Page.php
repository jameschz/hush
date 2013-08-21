<?php
/**
 * Ihush Page
 *
 * @category   Ihush
 * @package    Ihush_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Backend.php';
require_once 'Ihush/Acl/Backend.php';
require_once 'Ihush/Dao/Core.php';

/**
 * @package Ihush_App_Backend
 */
class Ihush_App_Backend_Page extends Ihush_App_Backend
{
	/**
	 * Do something before dispatch
	 * @see Hush_App_Dispatcher
	 */
	public function __init ()
	{
		// Auto load dao
		$this->dao = new Ihush_Dao();
		
		// Super admin
		$this->view->_sa = $this->sa = defined('__ACL_SA') ? __ACL_SA : 'sa';
		
		// Setting acl control object
		$this->view->_acl = $this->acl = Ihush_Acl_Backend::getInstance();
	}
	
	/**
	 * See if the user is logined
	 * @uses subclasses redirect to login page if user is not logined
	 * @return unknown
	 */
	public function authenticate ()
	{
		// check if login
		if (!$this->session('admin')) {
			$this->forward($this->root . 'auth/');
		}
		
		// set admin info object
		$this->view->_admin = $this->admin = $this->session('admin');
		
		// check if this path is accessable
		$path = parse_url($_SERVER['REQUEST_URI']);
		if ($this->acl instanceof Zend_Acl) {
			if ($this->acl->has($path['path'])) {
				if (!$this->acl->isAllowed($this->admin['role'], $path['path'])) {
					$this->forward($this->root . 'common/');
				}
			}
		}
	}
}