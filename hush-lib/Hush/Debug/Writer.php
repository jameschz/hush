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

/**
 * @see Hush_Debug_Writer_Html
 */
require_once 'Hush/Debug/Writer/Html.php';

/**
 * @abstract
 * @package Hush_Debug
 */
abstract class Hush_Debug_Writer
{
	/**
	 * Set debug level
	 *
	 * @param int $level
	 * @return void
	 */
	abstract public function level($level = 0);
	
	/**
	 * Set display styles, how to show the debug infomation
	 *
	 * @param string $style
	 * @return void
	 */
	abstract public function style($style = "");
	
	/**
	 * Add debug infomation to the pool to be displayed
	 *
	 * @param mixed $msg
	 * @param mixed $label
	 * @return void
	 */
	abstract public function debug($msg = null, $label = null);
	
	/**
	 * Write debug to the backend
	 *
	 * @return void
	 */
	abstract public function write();
}