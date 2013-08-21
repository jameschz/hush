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
require_once 'Ihush/Dao/Core/User.php';
require_once 'Ihush/Dao/Core/BpmFlow.php';
require_once 'Ihush/Dao/Core/BpmNode.php';
require_once 'Ihush/Dao/Core/BpmModel.php';
require_once 'Ihush/Dao/Core/BpmRequestAudit.php';
require_once 'Ihush/Paging.php';

/**
 * @package Ihush_Dao_Core
 */
class Core_BpmRequest extends Ihush_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_request';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_request_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		$this->t2 = Core_BpmRequestAudit::TABLE_NAME;
		$this->k2 = Core_BpmRequestAudit::TABLE_PRIM;
		$this->t3 = Core_BpmNode::TABLE_NAME;
		$this->k3 = Core_BpmNode::TABLE_PRIM;
		$this->t4 = Core_BpmFlow::TABLE_NAME;
		$this->k4 = Core_BpmFlow::TABLE_PRIM;
		$this->t5 = Core_BpmModel::TABLE_NAME;
		$this->k5 = Core_BpmModel::TABLE_PRIM;
		$this->t6 = Core_User::TABLE_NAME;
		$this->k6 = Core_User::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	/**
	 * used by RequestPage::sendListAction
	 */
	public function getSendByPage ($uid)
	{
		$eachPageNum = 10;
		$condition = $this->select()
			->join($this->t3, "{$this->t1}.{$this->k3} = {$this->t3}.{$this->k3}", null)
			->join($this->t4, "{$this->t1}.{$this->k4} = {$this->t4}.{$this->k4}", null)
			->where("{$this->t1}.author_id = ?", $uid)
			->order("{$this->t1}.{$this->k1} desc");
		$condition2 = clone $condition; // used to buid $sql_list
		$sql_count = $condition->from($this->t1, array("count(1) as total"));
		$sql_list = $condition2->from($this->t1, array("{$this->t1}.*", "{$this->t3}.bpm_node_name", "{$this->t4}.bpm_flow_name"))
			->limitPage(Hush_Util::param('p'), $eachPageNum);
		
		$total = $this->dbr()->fetchOne($sql_count);
		$pager = new Ihush_Paging($total, $eachPageNum, null, array(
			'Href' => '/request/sendList/p/{page}',
			'Mode' => 2,
		));
		
		return array(
			'list' => $this->dbr()->fetchAll($sql_list),
			'page' => $pager->toArray()
		);
	}
	
	/**
	 * used by RequestPage::recvListAction
	 */
	public function getRecvByPage ($uid = 0, $rid = array())
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "{$this->t2}.*", "{$this->t4}.bpm_flow_name"))
			->join($this->t4, "{$this->t1}.{$this->k4} = {$this->t4}.{$this->k4}", null)
			->join($this->t2, "{$this->t1}.{$this->k1} = {$this->t2}.{$this->k1} and {$this->t1}.bpm_request_status > 0 and {$this->t2}.bpm_request_audit_done = 0", null)
			->where("{$this->t2}.role_id IN (?)", implode(',', $rid))
			->orWhere("{$this->t2}.user_id = ?", $uid)
			->order("{$this->t1}.{$this->k1} desc");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	public function getDoneByPage ($uid = 0, $rid = array())
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "{$this->t2}.*", "{$this->t4}.bpm_flow_name"))
			->join($this->t4, "{$this->t1}.{$this->k4} = {$this->t4}.{$this->k4}", null)
			->join($this->t2, "{$this->t1}.{$this->k1} = {$this->t2}.{$this->k1} and {$this->t1}.bpm_request_status > 0 and {$this->t2}.bpm_request_audit_done = 1", null)
			->where("{$this->t2}.role_id IN (?)", implode(',', $rid))
			->orWhere("{$this->t2}.user_id = ?", $uid)
			->order("{$this->t1}.{$this->k1} desc");
		
		return $this->dbr()->fetchAll($sql);
	}
	
	public function getDetails ($reqId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "{$this->t2}.*", "{$this->t3}.bpm_node_name", "{$this->t6}.name as author_name"))
			->join($this->t3, "{$this->t1}.{$this->k3} = {$this->t3}.{$this->k3}", null)
			->join($this->t6, "{$this->t1}.author_id = {$this->t6}.{$this->k6}", null)
			->joinLeft($this->t2, "{$this->t1}.{$this->k1} = {$this->t2}.{$this->k2}", null)
			->where("{$this->t1}.{$this->k1} = ?", $reqId);
		
		$data = $this->dbr()->fetchRow($sql);
		
		// get field name hash
		$bpmModelDao = Ihush_Dao::load('Core_BpmModel');
		$fieldNameHash = $bpmModelDao->getFlowFieldNameHash((int) $data['bpm_flow_id']);
		
		// get request field hash
		$requestBodyHash = array();
		$requestBody = json_decode($data['bpm_request_body']);
		foreach ((array) $requestBody->field as $fieldId => $fieldVal) {
			$fieldName = isset($fieldNameHash[$fieldId]) ? (string) $fieldNameHash[$fieldId] : null;
			if ($fieldName) $requestBodyHash[$fieldName] = $fieldVal;
		}
		$data['bpm_request_body_hash'] = $requestBodyHash;
//		Hush_Util::dump($data);
		
		return $data;
	}
}