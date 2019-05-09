<?php
/**
 * APPNAME Page
 *
 * @category   APPNAME
 * @package    APPNAME_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'APPNAME/App/Default/Page.php';

/**
 * @package APPNAME_App_Default
 */
class CTRLNAMEPage extends APPNAME_App_Default_Page
{
	public function __init ()
	{
		parent::__init();
		
		// if page need login, uncomment this line
//		$this->authenticate();
	}
	
	public function indexAction () 
	{
		$this->view->welcome = 'Controller Index Page !';
	}
}
