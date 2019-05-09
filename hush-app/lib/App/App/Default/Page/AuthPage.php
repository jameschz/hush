<?php
/**
 * App Page
 *
 * @category   App
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/App/Default/Page.php';

/**
 * @package App_App_Default
 */
class AuthPage extends App_App_Default_Page
{
	public function indexAction () 
	{
		// TODO : Display directly
	}
	
	public function loginAction () 
	{
		// validate
		if (!$this->param('username') ||
			!$this->param('password')) {
			$this->addError('login.notempty');
		}
		elseif (!$this->param('securitycode')) {
			$this->addError('login.notcode');
		}
		elseif (strcasecmp($this->param('securitycode'),$this->session('securitycode'))) {
			$this->addError('login.badcode');
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
			elseif (is_numeric($admin)) {
				$this->addError('login.failed');
			} 
			// login ok
			else {
				// whether super admin
				$admin['sa'] = strcasecmp($admin['name'], $this->sa) ? false : true;
				// store admin into session
				$this->session('admin', $admin);
				$this->session('_area', '1');
				// redirect to homepage
				$this->forward($this->root);
			}
		}
		
		// also use index template
		$this->render('auth/index.tpl');
	}
	
	public function logoutAction ()
	{
		$redir_uri = '';
		$area = $this->session('_area');
		$admin = $this->session('admin');
		switch ($area) {
			case '1': // admin
				$redir_uri = '/auth/';
				break;
			case '2': // member
				$pubkey = cfg('core.publickey');
				include_once 'Crypt/RSA.php';
				$rsa = new Crypt_RSA();
				$rsa->loadKey($pubkey);
				$sdata = $rsa->encrypt($admin['m_id']);
				$sdata = Core_Util::urlsafe_b64encode($sdata);
				$redir_uri = $this->host_w.'/auth/logout?s='.$sdata;
				break;
			default:
				$redir_uri = '/';
				break;
		}
		
		// clear admin from session
		$this->session('admin', '');
		$this->session('_area', '');
		
		// clear shop from session
		$this->session('shops', '');
		$this->session('_shop', '');
		
		$this->forward($redir_uri);
	}
}
