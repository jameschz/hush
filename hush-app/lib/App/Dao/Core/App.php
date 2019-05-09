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
require_once 'App/Dao/Core/Role.php';

/**
 * @package App_Dao_Core
 */
class Core_App extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'app';
	
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
		$this->rsh = Core_AppRole::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
	
	/**
	 * Check whether name existed
	 * @param string $path
	 * @param int $app_id
	 * @return array
	 */
	public function checkName ($name, $app_id = -1)
	{
		if (!$name) return true; // escape
		
		$sql = $this->select()->from($this->t1, "*")->where("{$this->t1}.name = ?", $name);
		
		$res = $this->dbr()->fetchRow($sql);
		
		if (!$res || ($res && $res['id'] == $app_id)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Check whether path existed
	 * @param string $path
	 * @param int $app_id
	 * @return array
	 */
	public function checkPath ($path, $app_id = -1)
	{
		if (!$path) return true; // escape
		
		$sql = $this->select()->from($this->t1, "*")->where("{$this->t1}.path = ?", $path);
		
		$res = $this->dbr()->fetchRow($sql);
		
		if (!$res || ($res && $res['id'] == $app_id)) {
			return true;
		}
		
		return false;
	}
	
	/**
	 * Get all applications from track_app
	 * @return array
	 */
	public function getAllApps ($is_app = false)
	{
		$sql = $this->select()->from($this->t1, "*");
		
		if ($is_app) $sql->where("{$this->t1}.is_app = ?", 'YES');
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get acl data from relative tables
	 * For app's acl controls
	 * @see App_Acl_Backend
	 * @return array
	 */
	public function getAclApps ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.path as path", "{$this->t2}.id as role"))
			->join($this->rsh, "{$this->t1}.id = {$this->rsh}.app_id", null)
			->join($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null);
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all app data from track_acl_app
	 * Only for backend acl tools
	 * @return array
	 */
	public function getAppList ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "group_concat({$this->t2}.name) as role"))
			->join($this->rsh, "{$this->t1}.id = {$this->rsh}.app_id", null)
			->join($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null)
			->group("{$this->t1}.id");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all app tree's data from track_acl_app
	 * Only for backend acl tools
	 * @return array
	 */
	public function getAppTree ()
	{		
		return $this->getAppListByRole(null);
	}
	
	/**
	 * Get application list by role
	 * For getting menus for whole application
	 * @param int $role_id
	 * @return array
	 */
	public function getAppListByRole ($role_id)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "group_concat({$this->t2}.name) as role"))
			->joinLeft($this->rsh, "{$this->t1}.id = {$this->rsh}.app_id", null)
			->joinLeft($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null);
		
		if ($role_id) {
			if (is_array($role_id)) {
				$sql->where("{$this->rsh}.role_id in (?)", $role_id);
			} else {
				$sql->where("{$this->rsh}.role_id = ?", $role_id);
			}
		}
		
		$sql->group("{$this->t1}.id")
			->order(array("{$this->t1}.is_app desc", "{$this->t1}.pid", "{$this->t1}.order", "{$this->t1}.id"));
		
		$rawAppList = $this->dbr()->fetchAll($sql);
		
		// build app tree
		require_once 'Hush/Tree.php';
		$tree = new Tree();
		foreach ($rawAppList as $app) {
			$tree->setNode($app['id'], $app['pid'], $app);
		}
		
		// get top list
		$topAppList = array();
		$topAppListIds = $tree->getChild(0);
		foreach ($topAppListIds as $id) {
			$topAppList[$id] = $tree->getValue($id);
		}
		
		// get all list
		$allAppList = $topAppList;
		foreach ($topAppListIds as $tid) {
			$groupList = array(); // group list
			$groupListIds = $tree->getChild($tid);
			foreach ($groupListIds as $gid) {
				$groupAppList = array(); // group app list
				$groupList[$gid] = $tree->getValue($gid);
				$appListIds = $tree->getChild($gid);
				foreach ($appListIds as $aid) {
					$groupAppList[$aid] = $tree->getValue($aid);
				}
				$groupList[$gid]['list'] = $groupAppList;
			}
			$allAppList[$tid]['list'] = $groupList;
		}
		
//		Hush_Util::dump($allAppList);
		
		return $allAppList;
	}
	
	/**
	 * Update all app role from track_app_role
	 * @param int $id App ID
	 * @param array $roles Role ID's array
	 * @return bool
	 */
	 public function updateRoles ($id, $roles = array())
	 {
	 	if ($id) {
			$this->dbw()->delete($this->rsh, $this->dbw()->quoteInto("app_id = ?", $id));
	 	} else {
	 		return false;
	 	}
	 	
		if ($roles) {
			$cols = array('app_id', 'role_id');
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