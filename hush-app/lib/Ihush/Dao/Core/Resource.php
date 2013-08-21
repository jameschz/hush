<?php
/**
 * Ihush Dao
 *
 * @category   Ihush
 * @package    Ihush_Dao_Core
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/Dao/Core.php';
require_once 'Ihush/Dao/Core/ResourceRole.php';
require_once 'Ihush/Dao/Core/Role.php';

/**
 * @package Ihush_Dao_Core
 */
class Core_Resource extends Ihush_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'resource';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->t2 = Core_Role::TABLE_NAME;
		$this->rsh = Core_ResourceRole::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
	
	/**
	 * Get all resource from track_acl_resource
	 * @see Ihush_Acl
	 * @return array
	 */
	public function getAllResources ()
	{
		$sql = $this->select()->from($this->t1, "*");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get acl data from relative tables
	 * For app's acl controls
	 * @see Ihush_Acl_Backend
	 * @return array
	 */
	public function getAclResources ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.name as resource", "{$this->t2}.id as role"))
			->join($this->rsh, "{$this->t1}.id = {$this->rsh}.resource_id", null)
			->join($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null);
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all resource data from track_acl_resource
	 * Only for backend acl tools
	 * @return array
	 */
	public function getResourceList ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "group_concat({$this->t2}.name) as role"))
			->join($this->rsh, "{$this->t1}.id = {$this->rsh}.resource_id", null)
			->join($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null)
			->group("{$this->t1}.id");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Update all resource role from track_acl_resource_role
	 * @param int $id Resource ID
	 * @param array $roles Role ID's array
	 * @return bool
	 */
	 public function updateRoles ($id, $roles = array())
	 {
	 	if ($id) {
			$this->dbw()->delete($this->rsh, $this->dbw()->quoteInto("resource_id = ?", $id));
	 	} else {
	 		return false;
	 	}
	 	
		if ($roles) {
			$cols = array('resource_id', 'role_id');
			$vals = array();
			foreach ((array) $roles as $role) {
				$vals[] = array($id, $role);
			}
			if ($cols && $vals) {
				$this->dbw()->insertMultiRow($this->rsh, $cols, $vals);
				return true;
			}
		} else {
			return true;
		}
		
		return false;
	 }
}