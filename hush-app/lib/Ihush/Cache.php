<?php
/**
 * Ihush Cache
 *
 * @category   Ihush
 * @package    Ihush_Cache
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Cache.php';

/**
 * @package Ihush_Cache
 */
class Ihush_Cache
{
	/**
	 * @var Hush_Cache
	 */
	public static $_cache = null;
	
	/**
	 * Factory method for cache building
	 * 
	 * @param string $type Cache type supported 'File', 'Memcached' now
	 * @return Hush_Cache
	 */
	public static function factory ($type = 'File', $life_time = 0)
	{
		if ($life_time) {
			Hush_Cache::setLifeTime($life_time);
		}
		switch ($type) {
			case 'Memcached' :
				return Hush_Cache::factory('Memcached', array(
					'memcache_host' => Hush_Cache_Memcache::DEFAULT_HOST,
					'memcache_port' => Hush_Cache_Memcache::DEFAULT_PORT
				));
			default :
				return Hush_Cache::factory('File', array(
					'cache_dir' => __FILECACHE_DIR
				));
		}
	}
	
	/**
	 * Singleton method for getting cache instance
	 * 
	 * @param string $type Cache type supported 'File', 'Memcached' now
	 * @return Hush_Cache
	 */
	public static function getInstance ($type)
	{
		if (!self::$_cache) {
			self::$_cache = self::factory($type);
		}
		return self::$_cache;
	}
}