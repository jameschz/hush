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
require_once 'App/Dao/Core/BpmModelField.php';

/**
 * @package App_Dao_Core
 */
class Core_BpmModel extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_model';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_model_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		$this->t2 = Core_BpmModelField::TABLE_NAME;
		$this->k2 = Core_BpmModelField::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function getAllByFlowId ($flowId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*"))
			->where("{$this->t1}.bpm_flow_id = ?", $flowId);
		
		return $this->dbr()->fetchAll($sql);
	}
	
	/**
	 * used by BpmPage::adminNodeEditAction
	 */
	public function getFlowFieldList ($flowId)
	{
		$flowFieldList = array();
		
		$sql = $this->select()
			->from($this->t1, array("{$this->t2}.*"))
			->join($this->t2, "{$this->t1}.{$this->k1} = {$this->t2}.{$this->k1}", null)
			->where("{$this->t1}.bpm_flow_id = ?", $flowId);
		
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ($res as $row) {
			$modelId = $row['bpm_model_id'];
			$flowFieldList[$modelId][] = $row;
		}
		
		return $flowFieldList;
	}
	
	/**
	 * used by Core_BpmRequest::getDetails
	 */
	public function getFlowFieldNameHash ($flowId)
	{
		$flowFieldNameHash = array();
		
		$sql = $this->select()
			->from($this->t1, array("{$this->t2}.*"))
			->join($this->t2, "{$this->t1}.{$this->k1} = {$this->t2}.{$this->k1}", null)
			->where("{$this->t1}.bpm_flow_id = ?", $flowId);
		
		$res = $this->dbr()->fetchAll($sql);
		
		foreach ($res as $row) {
			$fieldId = $row['bpm_model_field_id'];
			$flowFieldNameHash[$fieldId] = $row['bpm_model_field_name'];
		}
		
		return $flowFieldNameHash;
	}
}