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
class HushPage extends App_App_Default_Page
{
	public function __init ()
	{
		parent::__init();
	}
	
	// common uploads
	public function uploadAction ()
	{
	    switch ($this->param('from')) {
	        case 'tinymce':
	            require_once 'Core/Util/Upload.php';
	            Core_Util_Upload::pic4mce(); // upload from tinymce
	            exit;
	        default:
	            exit;
	    }
	}
	
	// for tinymce ylink
	public function ylinkAction ()
	{
		$link = trim($this->param('link'));
		if ($link) {
			if (!Core_Util::str_test_url($link)) {
				ajax(1, '链接格式不对');
			}
// 			if (!Core_Util::str_test_mce_link($link)) {
// 				ajax(1, '链接不合法');
// 			}
			ajax(0, $link);
		}
		ajax(1, '未知错误');
	}
	
	// for tinymce ymedia
	public function ymediaAction ()
	{
		// 通用代码
		$code = trim($this->param('code'));
		if ($code) {
			if (!Core_Util::str_test_mce_video($code)) {
				ajax(1, '链接不合法');
			}
			$code = preg_replace('/width="?\'?[\d]+(px)?"?\'?\s/i','width="320" ',$code);
			$code = preg_replace('/height="?\'?[\d]+(px)?"?\'?\s/i', 'height="180" ',$code);
			ajax(0, $code);
		}
		ajax(1, '未知错误');
	}
}
