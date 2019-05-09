<?php
/**
 * App Acl
 *
 * @category   App
 * @package    App_Acl
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

require_once 'App/Acl.php';
require_once 'App/Dao.php';

/**
 * @package App_Acl
 */
class App_Acl_Default extends App_Acl
{	
	/**
	 * @var App_Acl_Default
	 */
	public static $_acl = null;
	
	/**
	 * Get static or cached acl object
	 * 
	 * @return App_Acl_Default
	 */
	public static function getInstance ()
	{
		if (!self::$_acl) {
			$class_name = __CLASS__;
			self::$_acl = new $class_name(); // init the acl object
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
		$roleDao = App_Dao::load('Core_Role');
		$roles = $roleDao->getAllRoles();
		foreach ((array) $roles as $role) {
			if (!isset($role['id'])) continue;
			$this->addRole(new Zend_Acl_Role($role['id']));
		}
		
		// add app for acl
		$appDao = App_Dao::load('Core_App');
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
		$resourceDao = App_Dao::load('Core_Resource');
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