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

/**
 * @package App_Dao_Core
 */
class Core_BpmNodePath extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_node_path';
	
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
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function getAllByFlowId ($flowId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"))
			->where("{$this->t1}.bpm_flow_id=?", $flowId);
		
		return $this->dbr()->fetchAll($sql);
	}
}