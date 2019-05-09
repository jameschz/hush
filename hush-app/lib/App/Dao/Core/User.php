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
require_once 'App/Dao/Core/Role.php';
require_once 'App/Dao/Core/UserRole.php';

/**
 * @package App_Dao_Core
 */
class Core_User extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'user';
	
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
		$this->rsh = Core_UserRole::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
	
	public function page ($cond = array(), $fields = array(), $pageNum = 10, $orders = array())
	{
		$fields = $fields ? $fields : array("*");
		$sql = $this->select()->from($this->t1, $fields);
		if (isset($cond['id']) && $cond['id']) $sql->where("id=?", $cond['id']);
		if (isset($cond['c_id']) && $cond['c_id']) $sql->where("c_id=?", $cond['c_id']);
		if (isset($cond['sup_id']) && $cond['sup_id']) $sql->where("sup_id=?", $cond['sup_id']);
		if (isset($cond['name']) && $cond['name']) $sql->where("name=?", $cond['name']);
	
		// page code
		require_once 'Core/Paging.php';
		Core_Paging::$pageNum = $pageNum;
		$page = Core_Paging::getInstance();
	
		// page code
		$sql->limit($page->limitNum, $page->offsetNum);
	
		if ($orders) {
			foreach ($orders as $order) $sql->order($order);
		} else {
			$sql->order("id desc");
		}
	
		$res = $this->dbr()->fetchAll($sql);
	
		// page code
		Core_Paging::simplePaging($page, $res);
	
		return array(
			'page' => $page->toArray(),
			'list' => $res,
		);
	}
	
	/**
	 * search by condition
	 */
	public function search ($cond = array(), $fields = array(), $orders = array(), $limit = 0)
	{
		$fields = $fields ? $fields : array("*");
		$sql = $this->select()->from($this->t1, $fields);
		if (isset($cond['id']) && $cond['id']) $sql->where("id=?", $cond['id']);
		if (isset($cond['c_id']) && $cond['c_id']) $sql->where("c_id=?", $cond['c_id']);
		if (isset($cond['sup_id']) && $cond['sup_id']) $sql->where("sup_id=?", $cond['sup_id']);
		if (isset($cond['name']) && $cond['name']) $sql->where("name=?", $cond['name']);
	
		// limit sql
		if ($limit) $sql->limit($limit);
	
		if ($orders) {
			foreach ($orders as $order) $sql->order($order);
		} else {
			$sql->order("id desc");
		}
	
		$res = $this->dbr()->fetchAll($sql);
	
		return $res;
	}
	
	/**
	 * Login function
	 * @uses Used by user login process
	 * @param string $user
	 * @param string $pass
	 * @return bool or array
	 */
	public function authenticate ($user, $pass)
	{
		$sql = $this->select()
			->from($this->t1, "*")
			->where("name = ?", $user);
		
		$user = $this->dbr()->fetchRow($sql);
		
		if (!$user['id'] || !$user['pass']) return false;
		
		if (strcmp($user['pass'], Hush_Util::md5($pass))) return $user['id'];
		
		$sql = $this->select()
			->from($this->t2, "*")
			->join($this->rsh, "{$this->t2}.id = {$this->rsh}.role_id", null)
			->where("{$this->rsh}.user_id = ?", $user['id']);
		
		$roles = $this->dbr()->fetchAll($sql);
		
		if (!sizeof($roles)) return false;
		
		foreach ($roles as $role) {
			$user['role'][] = $role['id'];
			$user['priv'][] = $role['alias'];
		}
		
		return $user;
	}
	
	/**
	 * Get all user data from track_acl_user
	 * @see App_Acl
	 * @return array
	 */
	public function getAllUsers ()
	{
		$sql = $this->select()->from($this->t1, "*");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Get all user data from track_acl_user
	 * Only for backend acl tools
	 * @return array
	 */
	public function getUserList ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "group_concat({$this->t2}.name) as role"))
			->joinLeft($this->rsh, "{$this->t1}.id = {$this->rsh}.user_id", null)
			->joinLeft($this->t2, "{$this->t2}.id = {$this->rsh}.role_id", null)
			->group("{$this->t1}.id");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * Update all user role from track_acl_user_role
	 * @param int $id User ID
	 * @param array $roles Role ID's array
	 * @return bool
	 */
	 public function updateRoles ($id, $roles = array())
	 {
	 	if ($id) {
			$this->dbw()->delete($this->rsh, $this->dbw()->quoteInto("user_id = ?", $id));
	 	} else {
	 		return false;
	 	}
	 	
		if ($roles) {
			$cols = array('user_id', 'role_id');
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