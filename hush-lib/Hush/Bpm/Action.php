<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Bpm
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Hush_Bpm
 */
abstract class Hush_Bpm_Action
{
	/**
	 * Get action for saving operation
	 * @param string $action
	 */
	abstract public function get ($action);
}