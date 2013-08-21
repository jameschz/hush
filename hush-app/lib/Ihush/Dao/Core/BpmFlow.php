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
require_once 'Ihush/Dao/Core/BpmFlowRole.php';

/**
 * @package Ihush_Dao_Core
 */
class Core_BpmFlow extends Ihush_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_flow';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_flow_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		$this->rsh = Core_BpmFlowRole::TABLE_NAME;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function getByPage ()
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"));
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * used by RequestPage::selectFlowAction
	 */
	public function getByRole ($role_id)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"))
			->joinLeft($this->rsh, "{$this->t1}.{$this->k1} = {$this->rsh}.{$this->k1} and {$this->t1}.bpm_flow_status > 0", null);
		
		if (is_array($role_id)) {
			$sql->where("{$this->rsh}.role_id in (?)", $role_id);
		} else {
			$sql->where("{$this->rsh}.role_id = ?", $role_id);
		}
		
		return $this->dbr()->fetchAll($sql);
	}
	
	public function updateRoles ($id, $roles = array())
	{
		if ($id) {
			$this->dbw()->delete($this->rsh, $this->dbw()->quoteInto("{$this->k1} = ?", $id));
		} else {
			return false;
		}
		
		if ($roles) {
			$cols = array($this->k1, 'role_id');
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