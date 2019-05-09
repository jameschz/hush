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
 
require_once 'Hush/Acl.php';

/**
 * @package App_Acl
 */
class App_Acl extends Hush_Acl
{
	/**
	 * Judge the privilege is allowed
	 * @param mixed $roles
	 * @param string $resource
	 * @param string $privilege
	 * @return bool
	 */
	public function isAllowed ($roles = null, $resource = null, $privilege = null) 
	{
		if (!is_array($roles)) {
			return parent::isAllowed($roles, $resource, $privilege);
		}
		foreach ((array) $roles as $role) {
			if (parent::isAllowed($role, $resource, $privilege)) {
				return true;
			}
		}
		return false;
	}
}
