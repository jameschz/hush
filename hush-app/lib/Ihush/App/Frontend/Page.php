<?php
/**
 * Ihush Page
 *
 * @category   Ihush
 * @package    Ihush_App_Frontend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Page.php';
require_once 'Ihush/Dao/Core.php';
require_once 'Ihush/Dao/Apps.php';

/**
 * @package Ihush_App_Frontend
 */
class Ihush_App_Frontend_Page extends Ihush_App_Page
{
	/**
	 * Do something before dispatch
	 * @see Hush_App_Dispatcher
	 */
	public function __init ()
	{
		// Auto load dao
		$this->dao = new Ihush_Dao();
	}
}