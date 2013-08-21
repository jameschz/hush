<?php
/**
 * Ihush Dao
 *
 * @category   Ihush
 * @package    Ihush_Bpm_Lang
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Hush/Bpm/Lang/Pbel.php';
require_once 'Ihush/Dao.php';

/**
 * @package Ihush_Bpm_Lang
 */
class Ihush_Bpm_Lang_Pbel extends Hush_Bpm_Lang_Pbel
{
	/**
	 * 
	 */
	public function __construct ()
	{
		// set file for getDocs
		$this->setFile(__FILE__);
		
		// init dao class
		$this->dao = new Ihush_Dao();
		
		// get from uri or global vars
		$this->reqId = Hush_Util::param('reqId') ? Hush_Util::param('reqId') : $GLOBALS['reqId'];
	}
	
	/**
	 * @pbel 添加模型 : model_form_add(模型ID)
	 */
	public function model_form_add ($modelId)
	{
		$bpmModelDao = $this->dao->load('Core_BpmModel');
		$modelData = $bpmModelDao->read($modelId);
		return $this->setReturn($modelData['bpm_model_form']);
	}
	
	/**
	 * @pbel 编辑模型 : model_form_edit(模型ID)
	 */
	public function model_form_edit ($modelId)
	{
		$bpmModelDao = $this->dao->load('Core_BpmModel');
		$modelData = $bpmModelDao->read($modelId);
		return $this->setReturn($modelData['bpm_model_form']);
	}
	
	/**
	 * @pbel 获取字段 : model_field(字段ID)
	 */
	public function model_field ($fieldId)
	{
		if ($this->reqId && $fieldId) {
			$bpmRequestDao = $this->dao->load('Core_BpmRequest');
			$requestData = $bpmRequestDao->read($this->reqId);
			$requestJson = json_decode(trim($requestData['bpm_request_body']));
			return $this->setReturn($requestJson->field->$fieldId);
		}
		return $this->setReturn(false);
	}
	
	/**
	 * @pbel 角色审核 : audit_by_role(角色ID)
	 */
	public function audit_by_role ($roleId)
	{
		if ($this->reqId && $roleId) {
			// insert request audit data
			$bpmRequestAuditDao = $this->dao->load('Core_BpmRequestAudit');
			$auditData['bpm_request_audit_status'] = 0; // default
			$auditData['bpm_request_audit_done'] = 0; // not done
			$auditData['bpm_request_id'] = $this->reqId;
			$auditData['role_id'] = $roleId;
			$bpmRequestAuditDao->replace($auditData);
			return $this->setReturn(true);
		}
		return $this->setReturn(false);
	}
	
	/**
	 * @pbel 用户审核 : audit_by_user(用户ID)
	 */
	public function audit_by_user ($userId)
	{
		if ($this->reqId && $userId) {
			// insert request audit data
			$bpmRequestAuditDao = $this->dao->load('Core_BpmRequestAudit');
			$auditData['bpm_request_audit_status'] = 0; // default
			$auditData['bpm_request_audit_done'] = 0; // not done
			$auditData['bpm_request_id'] = $this->reqId;
			$auditData['user_id'] = $userId;
			$bpmRequestAuditDao->replace($auditData);
			return $this->setReturn(true);
		}
		return $this->setReturn(false);
	}
	
	/**
	 * @pbel 审核结果 : audit_check()
	 */
	public function audit_check ()
	{
		if ($this->reqId) {
			// get field name
			$bpmRequestAuditDao = $this->dao->load('Core_BpmRequestAudit');
			$isAudit = $bpmRequestAuditDao->isAudit($this->reqId);
			return $this->setReturn($isAudit);
		}
		return $this->setReturn(false);
	}
	
	/**
	 * @pbel 跳转节点 : forward(节点ID)
	 */
	public function forward ($nodeId)
	{
		$redirUrl = (string) $_SERVER['REQUEST_URI'];
		$redirUrl = preg_replace('/(nodeId\/[0-9]+)/i', 'nodeId/'.$nodeId, $redirUrl);
		Hush_Util::headerRedirect($redirUrl);
		exit;
	}
}