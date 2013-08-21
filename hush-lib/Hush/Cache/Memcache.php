<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Cache
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id: james $
 */
 
/**
 * @see Hush_Exception
 */
require_once 'Hush/Cache.php';

/**
 * @package Hush_Cache
 */
class Hush_Cache_Memcache extends Hush_Cache
{
	const DEFAULT_HOST = '127.0.0.1';
	const DEFAULT_PORT = '11211';
}