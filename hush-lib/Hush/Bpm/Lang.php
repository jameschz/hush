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
abstract class Hush_Bpm_Lang
{
	/**
	 * Prepare Pbel code
	 */
	abstract public function prepare ($code);
	
	/**
	 * Execute Pbel code
	 */
	abstract public function execute ($code);
}