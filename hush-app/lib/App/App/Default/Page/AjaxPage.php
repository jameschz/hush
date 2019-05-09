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
 
require_once 'AdminPage.php';

/**
 * @package App_App_Default
 */
class AjaxPage extends AdminPage
{
	public function __init ()
	{
		parent::__init();
	}
	
	public function qrcodeAction ()
	{
		$s = urldecode($this->param('s'));
		if ($s) {
			require_once 'Core/Qrcode.php';
			$back_color = 0xFFFFFF;
			$fore_color = 0x000000;
			QRcode::png($s, false, 'h', 20, 1, false, $back_color, $fore_color);
		}
	}
	
	public function finduserAction ()
	{
	    $cond = array();
	    if ($this->param('s')) {
	        $cond['sname'] = $this->param('s');
	    }
	    // 		$cond['status'] = array(); // only open
	    $dao = $this->dao->load('Core_User');
	    $res = $dao->search($cond, array('id','name'));
	    if ($res) {
	        Core_Util::app_ajax_result(ERR_OK, '', $res);
	    }
	    Core_Util::app_ajax_result(ERR_SYS, '');
	}
}
