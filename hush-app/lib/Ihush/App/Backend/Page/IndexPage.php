<?php
/**
 * iHush Track
 *
 * @category   Track
 * @package    Ihush_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Backend/Page.php';

/**
 * @package Ihush_App_Backend
 */
class IndexPage extends Ihush_App_Backend_Page
{	
	public function __init ()
	{
		parent::__init(); // overload parent class's method
		$this->authenticate();
	}
	
	public function indexAction () 
	{
		// get app menu list
		$appDao = $this->dao->load('Core_App');
		$appList = $appDao->getAppListByRole($this->admin['role']);
		$this->view->appList = $appList;
	}
}