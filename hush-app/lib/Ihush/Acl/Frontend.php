<?php
/**
 * Ihush Acl
 *
 * @category   Ihush
 * @package    Ihush_Acl
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'Ihush/Acl.php';
require_once 'Ihush/Dao.php';

/**
 * @package Ihush_Acl
 */
class Ihush_Acl_Frontend extends Ihush_Acl
{
	/**
	 * @var Ihush_Acl_Frontend
	 */
	public static $_acl = null;
	
	/**
	 * Get static or cached acl object
	 * 
	 * @return Ihush_Acl_Frontend
	 */
	public static function getInstance ()
	{
		if (!self::$_acl) {
			$class_name = __CLASS__;
			require_once 'Ihush/Cache.php';
			// we store cache in 60 seconds
			$cache = Ihush_Cache::factory('File', 60);
			if (!(self::$_acl = $cache->load($class_name))) {
				self::$_acl = new $class_name(); // init the acl object
				$cache->save(self::$_acl, $class_name);
			}
		}
		return self::$_acl;
	}
	
	/**
	 * Construct
	 * Init the acl object
	 * @return unknown
	 */
	public function __construct () 
	{		
		// TODO : initialize the frontend acl object
	}
}