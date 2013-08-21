<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Exception
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Hush_Exception
 */
class Hush_Exception extends Exception
{
	public function __construct($msg = '', $code = 0, Exception $e = null)
	{
		if (version_compare(PHP_VERSION, '5.3.0', '<')) {
			parent::__construct($msg, (int) $code);
		} else {
			parent::__construct($msg, (int) $code, $e);
		}
	}
}