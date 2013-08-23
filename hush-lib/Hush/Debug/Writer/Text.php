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
class Hush_Debug_Writer_Text extends Hush_Debug_Writer
{	
    /**
     * Debug level
     *
     * @var int
     */
	protected $_level = 0;
	
    /**
     * Debug display styles
     *
     * @var string
     */
	protected $_style = 'default';
	
    /**
     * Valid debug display style types
     *
     * @var array
     */
	protected $_styleTypes = array('default', 'bottom', 'top');
	
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
	 * Get template for debug
	 * @return string
	 */
	protected function _loadtpl ()
	{
		switch ($this->_style) {
			default :
				return '{DEBUGMSG}';
		}
		return null;
	}
}
?>