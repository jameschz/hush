<?php
/**
 * Ihush App
 *
 * @category   Ihush
 * @package    Ihush_App
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Exception.php';

/**
 * @package Ihush_App
 */
class Ihush_App_Exception extends Hush_Exception
{
	public function __construct($msg = '', $code = 0, Exception $e = null)
	{
		parent::__construct($msg, $code, $e);
	}
	
	public function __toString() {
		return __CLASS__ . ": [{$this->code}]: {$this->message}\n";
	}
}