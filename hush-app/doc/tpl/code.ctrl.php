<?php
/**
 * APPNAME Page
 *
 * @category   APPNAME
 * @package    APPNAME_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'APPNAME/App/Backend/Page.php';

/**
 * @package APPNAME_App_Backend
 */
class CTRLNAMEPage extends APPNAME_App_Backend_Page
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
