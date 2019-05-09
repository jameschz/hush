<?php
/**
 * App Dao
 *
 * @category   App
 * @package    App_Dao_Core
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/Dao/Core.php';
require_once 'App/Dao/Core/AppRole.php';
require_once 'App/Dao/Core/UserRole.php';
require_once 'App/Dao/Core/ResourceRole.php';
require_once 'App/Dao/Core/BpmFlowRole.php';
require_once 'App/Dao/Core/RolePriv.php';

/**
 * @package App_Dao_Core
 */
class Core_Role extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'role';
	
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
		$this->rsh1 = Core_UserRole::TABLE_NAME;
		$this->rsh2 = Core_AppRole::TABLE_NAME;
		$this->rsh3 = Core_ResourceRole::TABLE_NAME;
		$this->rsh4 = Core_RolePriv::TABLE_NAME;
		$this->rsh5 = Core_BpmFlowRole::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
	
	/**
	 * Get user's role data by user id
	 * Include the user's roles
	 * @param int $id User ID
	 * @param array $privs Allowed privileges
	 * @return array
	 */
	public function getRoleByUserId ($id, $privs = array())
	{
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh1, "{$this->t1}.id = {$this->rsh1}.role_id", null)
			->where("{$this->rsh1}.user_id=?", $id);
			
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ((array) $res as $k => $v) {
			if (!in_array($v['id'], $privs)) $res[$k]['readonly'] = true;
		}
		
		return $res;
	}
	
	/**
	 * Get app's role data by app id
	 * Include the app's roles
	 * @param int $id App ID
	 * @param array $privs Allowed privileges
	 * @return array
	 */
	public function getRoleByAppId ($id, $privs = array())
	{
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh2, "{$this->t1}.id = {$this->rsh2}.role_id", null)
			->where("{$this->rsh2}.app_id=?", $id);
		
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ((array) $res as $k => $v) {
			if (!in_array($v['id'], $privs)) $res[$k]['readonly'] = true;
		}
		
		return $res;
	}
	
	/**
	 * Get resource's role data by resource id
	 * Include the resource's roles
	 * @param int $id Resource ID
	 * @param array $privs Allowed privileges
	 * @return array
	 */
	public function getRoleByResourceId ($id, $privs = array())
	{
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh3, "{$this->t1}.id = {$this->rsh3}.role_id", null)
			->where("{$this->rsh3}.resource_id=?", $id);
		
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ((array) $res as $k => $v) {
			if (!in_array($v['id'], $privs)) $res[$k]['readonly'] = true;
		}
		
		return $res;
	}
	
	/**
	 * Get flow's role data by flow id
	 * Include the flow's roles
	 * @param int $id Flow ID
	 * @param array $privs Allowed privileges
	 * @return array
	 */
	public function getRoleByFlowId ($id, $privs = array())
	{
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh5, "{$this->t1}.id = {$this->rsh5}.role_id", null)
			->where("{$this->rsh5}.bpm_flow_id=?", $id);
		
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ((array) $res as $k => $v) {
			if (!in_array($v['id'], $privs)) $res[$k]['readonly'] = true;
		}
		
		return $res;
	}
	
	/**
	 * Get all roles data from track_acl_role
	 * @return array
	 */
	public function getAllRoles ()
	{
		$sql = $this->select()->from($this->t1, "*");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all roles data from track_acl_role
	 * Only for backend acl tools
	 * @return array
	 */
	public function getRoleList ()
	{
		// Join self demo !!!
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "group_concat({$this->t1}_2.name) as role"))
			->joinLeft($this->rsh4, "{$this->t1}.id = {$this->rsh4}.role_id", null)
			->joinLeft($this->t1, "{$this->t1}_2.id = {$this->rsh4}.priv_id", null)
			->group("{$this->t1}.id");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all role's priv data
	 * Only for privilege management
	 * @param array $roles Role ID array (from session)
	 * @return array
	 */
	public function getAllPrivs ($roles)
	{
		if (!$roles || !sizeof($roles)) return array();
		
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh4, "{$this->t1}.id = {$this->rsh4}.priv_id", null)
			->where("{$this->rsh4}.role_id IN (?)", implode(',', $roles));
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get user's priv data by role id
	 * That is getting user accessed role list
	 * @param int $id Role ID
	 * @return array
	 */
	public function getPrivByRoleId ($id)
	{
		$sql = $this->select()
			->from($this->t1, "{$this->t1}.*")
			->join($this->rsh4, "{$this->t1}.id = {$this->rsh4}.priv_id", null)
			->where("{$this->rsh4}.role_id=?", $id);
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Update all role priv from track_acl_role_priv
	 * @param int $id Role ID
	 * @param array $privs Role's privs array (that is also role id)
	 * @return bool
	 */
	public function updatePrivs ($id, $privs = array())
	{
		if ($id) {
			$this->dbw()->delete($this->rsh4, $this->dbw()->quoteInto("role_id = ?", $id));
		} else {
			return false;
		}
		
		if ($privs) {
			$cols = array('role_id', 'priv_id');
			$vals = array();
			foreach ((array) $privs as $priv) {
				$vals[] = array($id, $priv);
			}
			if ($cols && $vals) {
				$this->dbw()->insertMultiRow($this->rsh4, $cols, $vals);
				return true;
			}
		} else {
			return true;
		}
		
		return false;
	}
}