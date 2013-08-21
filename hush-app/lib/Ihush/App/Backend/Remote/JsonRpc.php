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
class JsonRpc extends Ihush_App_Backend_Page
{
	public function server () 
	{
		echo '<b>$_REQUEST >>></b>';
		Hush_Util::dump($_REQUEST);
		echo '<b>func_get_args() >>></b>';
		Hush_Util::dump(func_get_args());
	}
}
