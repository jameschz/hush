<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Cache
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
/**
 * @see Zend_Cache
 */
require_once 'Zend/Cache.php';

/**
 * Include subclasses
 */
require_once 'Hush/Cache/Memcache.php';

/**
 * @package Hush_Cache
 */
class Hush_Cache
{
	/**
	 * Default expire time
	 * @staticvar int
	 */
	public static $life_time = 9999999999; // default never overtime
	
	/**
	 * @staticvar Zend_Cache
	 */
	public static $cache_object = null;
	
	/**
	 * Set cache expire time
	 * @static
	 * @param int $second
	 */
	public static function setLifeTime ($second) 
	{
		self::$life_time = $second;
	}
	
	/**
	 * Singleton method
	 * @static
	 * @param string $type
	 * @return Zend_Cache
	 */
	public static function factory ($type = 'File', $options = array()) 
	{
		if (!self::$cache_object) 
		{
			$front_opt = array(
				'automatic_serialization' => true
			);
			
			if (self::$life_time) {
				$front_opt['lifeTime'] = self::$life_time;
			}
			
			switch ($type) {
				case 'Memcached' :
					$mhost = $options['memcache_host'];
					$mport = $options['memcache_port'];
					$memcache = new Memcache;
					if (!@$memcache->connect($mhost, $mport)) {
						require_once 'Hush/Cache/Exception.php';
						throw new Hush_Cache_Exception('Can not connect to memcache server');
					}
					$back_opt = array(
						'servers' => array(
							'host'	=> $mhost,
							'port'	=> $mport
						)
					);
					break;
				default :
					if (!is_dir($options['cache_dir'])) {
                        if (realpath(__DAT_DIR)) {
                            mkdir(realpath(__DAT_DIR) . '/cache', 0600);
                            $options['cache_dir'] = realpath(__DAT_DIR . '/cache');
                        } else {
                            require_once 'Hush/Cache/Exception.php';
                            throw new Hush_Cache_Exception('Can not found cache_dir file directory');
                        }
					}
					$back_opt = array(
						'cache_dir' => $options['cache_dir']
					);
					break;
			}
			
			self::$cache_object = Zend_Cache::factory(
				'Core',		// available frontends : Core, Output, Class, File, Function, Page
				$type,		// available backends : File, Sqlite, Memcached, Apc, ZendPlatform, Xcache, TwoLevels
				$front_opt,
				$back_opt
			);
		}
		
		return self::$cache_object;
	}
}
