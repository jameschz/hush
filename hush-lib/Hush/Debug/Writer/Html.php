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

require_once 'Hush/Debug/Writer.php';

/**
 * @package Hush_Debug
 */
class Hush_Debug_Writer_Html extends Hush_Debug_Writer
{	
    /**
     * Debug level
     *
     * @var int
     */
	private $_level = 0;
	
    /**
     * Debug display styles
     *
     * @var string
     */
	private $_style = 'default';
	
    /**
     * Valid debug display style types
     *
     * @var array
     */
	private $_styleTypes = array('default', 'bottom', 'top');
	
    /**
     * Debug message
     *
     * @var array
     */
	public static $_debug_msg = array();
	
	/**
	 * Construct
	 * 
	 */
	public function __construct ($levels = array()) 
	{
		$this->_levels = $levels;
	}
	
	/**
	 * @see Hush_Debug_Writer
	 */
	public function level ($level = 0) 
	{
		$this->_level = $level;
	}
	
	/**
	 * @see Hush_Debug_Writer
	 */
	public function style ($style = 'default') 
	{
		$style = strtolower($style);
		if (!in_array($style, $this->_styleTypes)) {
			require_once 'Hush/Debug/Exception.php';
			throw new Hush_Debug_Exception(sprintf('Invalid debug display style "%s"; cannot retrieve', $style));
		}
		$this->_style = $style;
		return $this;
	}
	
	/**
	 * @see Hush_Debug_Writer
	 */
	public function debug ($msg = null, $label = null, $level = 0) 
	{
		self::$_debug_msg[] = array(
			'value' => Zend_Debug::dump($msg, $label, false),
			'level' => $level
		);
		return $this;
	}
	
	/**
	 * @see Hush_Debug_Writer
	 */
	public function write ($echo = true) 
	{
		$out = '';
		$tpl = $this->_loadtpl();

		if (sizeof(self::$_debug_msg) > 0) {
			// get all msg
			foreach (self::$_debug_msg as $msg) {
				$level = isset($msg['level']) ? $msg['level'] : 0;
				$value = isset($msg['value']) ? $msg['value'] : 0;
				// msg level must above debug level
				if ($level < $this->_level) {
					continue;
				}
				// decode once for xdebug extension
				if (function_exists('xdebug_disable')) {
					$out .= '<div>'.html_entity_decode($value).'</div>';
				} else {
					$out .= '<div>'.$value.'</div>';
				}
			}
			// fill tpl
			if ($out && $tpl) {
				$out = str_replace('{DEBUGMSG}', $out, $tpl);
			}
		}
		
		if ($echo !== true) {
			return $out;
		} else {
			echo $out;
		}
	}
	
	/**
	 * Html template for debug
	 * @return string
	 */
	private function _loadtpl ()
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