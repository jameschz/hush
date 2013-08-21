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
class Ihush_Acl_Backend extends Ihush_Acl
{	
	/**
	 * @var Ihush_Acl_Backend
	 */
	public static $_acl = null;
	
	/**
	 * Get static or cached acl object
	 * 
	 * @return Ihush_Acl_Backend
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
		// add roles for acl
		$roleDao = Ihush_Dao::load('Core_Role');
		$roles = $roleDao->getAllRoles();
		foreach ((array) $roles as $role) {
			if (!isset($role['id'])) continue;
			$this->addRole(new Zend_Acl_Role($role['id']));
		}
		
		// add app for acl
		$appDao = Ihush_Dao::load('Core_App');
		$apps = $appDao->getAllApps();
		foreach ((array) $apps as $app) {
			if (!strlen($app['path'])) continue;
			$this->add(new Zend_Acl_Resource($app['path']));
		}
		
		// add app and role's relation for acl
		$aclApps = $appDao->getAclApps();
		foreach ((array) $aclApps as $acl) {
			if (!strlen($acl['role'])) continue;
			if (!strlen($acl['path'])) continue;
			$this->allow($acl['role'], $acl['path']);
		}
		
		// add resource for acl
		$resourceDao = Ihush_Dao::load('Core_Resource');
		$resources = $resourceDao->getAllResources();
		foreach ((array) $resources as $resource) {
			if (!strlen($resource['name'])) continue;
			$this->add(new Zend_Acl_Resource($resource['name']));
		}
		
		// add resource and role's relation for acl
		$aclResources = $resourceDao->getAclResources();
		foreach ((array) $aclResources as $acl) {
			if (!strlen($acl['role'])) continue;
			if (!strlen($acl['resource'])) continue;
			$this->allow($acl['role'], $acl['resource']);
		}
	}
}