<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Debug
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: james $
 */

require_once 'Hush/Debug/Writer/Text.php';

/**
 * @package Hush_Debug
 */
class Hush_Debug_Writer_Html extends Hush_Debug_Writer_Text
{	
	/**
	 * Override
	 * @return string
	 */
	protected function _loadtpl ()
	{
		switch ($this->_style) {
			case 'default' :
			case 'bottom' :
				$style_pos = 'left:0px;bottom:0px';
				break;
			case 'top' :
				$style_pos = 'left:0px;top:0px';
				break;
			default :
				$style_pos = null;
				break;
		}
		if ($style_pos) {
			$tpl = '<style>body{width:100%;overflow:auto;font-family:Tahoma,Verdana,Arial,Helvetica,sans-serif;font-size:10pt}'
				 . '#debug_box{position:fixed;-position:absolute;*position:absolute;bottom:0;z-index:99999;height:320px;width:100%;border-top:red solid 2px;background:#ffffe0;*top:expression(eval(document.compatMode && document.compatMode=="CSS1Compat") ? documentElement.scrollTop+(documentElement.clientHeight-this.clientHeight):document.body.scrollTop+(document.body.clientHeight-this.clientHeight));}'
				 . '#debug_box_nav{height:20px;width:auto;padding-top:2px;padding-left:10px;background:#666;color:#fff;font-weight:bold;cursor:pointer;}'
				 . '#debug_box_body{height:300px;width:auto;overflow:auto;padding-left:10px;border-top:red solid 2px;}</style>'
				 . '<div id="debug_box" style="'.$style_pos.'">'
				 . '<div id="debug_box_nav">Toggle Debug Info (NO IE6) ></div>'
				 . '<div id="debug_box_body">{DEBUGMSG}</div></div>'
				 . '<script>var db=document.getElementById("debug_box");var dbn=document.getElementById("debug_box_nav");var dbb=document.getElementById("debug_box_body");'
				 . 'function close_debug(){db.style.height="20px";dbb.style.display="none";}'
				 . 'function open_debug(){db.style.height="320px";dbb.style.display="";}'
				 . 'dbn.onclick=function(){if(dbb.style.display!="none"){close_debug();}else{open_debug()}};'
				 . 'dbb.scrollTop = dbb.scrollHeight;close_debug();</script>';
			return "\n<!-- Debug Info Html -->\n".$tpl;
		}
		return null;
	}
}
?>