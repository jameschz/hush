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
 
require_once 'App/App/Default.php';
require_once 'App/Acl/Default.php';

/**
 * @package App_App_Default
 */
class App_App_Default_Page extends App_App_Default
{
    /**
     * Do something before dispatch
     * @see Hush_App_Dispatcher
     */
    public function __init ()
    {
        // Auto load dao
        $this->__init_dao();
        
        // Auto load service
        $this->__init_service();
        
        // Super admin
        $this->view->_sa = $this->sa = defined('__ACL_SA') ? __ACL_SA : 'sa';
        
        // Setting acl control object
        $this->view->_acl = $this->acl = App_Acl_Default::getInstance();
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
        $path = parse_url(preg_replace('/\/{2,}/i', '/', rtrim($_SERVER['REQUEST_URI'], '/')));
        if ($this->acl instanceof Zend_Acl) {
            if ($this->acl->has($path['path'])) {
                if (!$this->acl->isAllowed($this->admin['role'], $path['path'])) {
                    $this->forward($this->root . 'acl/welcome');
                }
            }
        }
    }
    
}