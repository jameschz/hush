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
require_once 'App/Dao/Core/BpmNodePath.php';

/**
 * @package App_Dao_Core
 */
class Core_BpmNode extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_node';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_node_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		$this->t2 = Core_BpmNodePath::TABLE_NAME;
		$this->k2 = Core_BpmNodePath::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	/**
	 * used by BpmPage::adminNodeEditAction
	 */
	public function checkNodes ($flowId, $nodeIds)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.bpm_node_id"))
			->where("{$this->t1}.bpm_flow_id=?", $flowId);
		
		// get all node ids
		$nodeList = array();
		$res = $this->dbr()->fetchAll($sql);
		foreach ((array) $res as $row) {
			$nodeList[] = (int) $row['bpm_node_id'];
		}
		
		// check each node id
		foreach ((array) $nodeIds as $nodeId) {
			if (!in_array($nodeId, $nodeList)) return false;
		}
		
		return true;
	}
	
	public function canBeRemoved ($nodeId)
	{
		$sql = $this->select()
			->from($this->t2, array("count(1)"))
			->where("{$this->t2}.bpm_node_id_from=?", $nodeId)
			->orWhere("{$this->t2}.bpm_node_id_to=?", $nodeId);
		
		return $this->dbr()->fetchOne($sql);
	}
	
	public function getAllByFlowId ($flowId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"))
			->where("{$this->t1}.bpm_flow_id=?", $flowId);
		
		return $this->dbr()->fetchAll($sql);
	}
	
	public function getFirstByFlowId ($flowId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"))
			->where("{$this->t1}.bpm_node_attr=?", 1)
			->where("{$this->t1}.bpm_flow_id=?", $flowId);
		
		return $this->dbr()->fetchRow($sql);
	}
}