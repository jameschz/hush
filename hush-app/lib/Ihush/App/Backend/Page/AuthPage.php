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
 
require_once 'Ihush/App/Backend/Page.php';

/**
 * @package Ihush_App_Backend
 */
class AuthPage extends Ihush_App_Backend_Page
{
	public function indexAction () 
	{
		// TODO : Display directly
	}
	
	public function loginAction () 
	{
		// validate
		if (!$this->param('username') ||
			!$this->param('password') ||
			!$this->param('securitycode')) {
			$this->addError('login.notempty');
		}
		elseif (strcasecmp($this->param('securitycode'),$this->session('securitycode'))) {
			$this->addError('common.scodeerr');
		}
		
		// login process
		if ($this->noError()) {
			$aclUserDao = $this->dao->load('Core_User');
			$admin = $aclUserDao->authenticate($this->param('username'), $this->param('password'));
			// login failed
			if (!$admin) {
				$this->addError('login.nouser');
			}
			// login failed 
			elseif (is_int($admin)) {
				$this->addError('login.failed');
			} 
			// login ok
			else {
				// whether super admin
				$admin['sa'] = strcasecmp($admin['name'], $this->sa) ? false : true;
				// store admin into session
				$this->session('admin', $admin);
				// redirect to homepage
				$this->forward($this->root);
			}
		}
		
		// also use index template
		$this->render('auth/index.tpl');
	}
	
	public function logoutAction ()
	{
		if ($this->session('admin')) {
			// clear admin from session
			$this->session('admin', '');
		}
		
		$this->forward($this->root);
	}
}
