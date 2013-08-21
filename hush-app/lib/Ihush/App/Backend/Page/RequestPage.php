<?php
/**
 * Ihush Page
 *
 * @category   Ihush
 * @package    Ihush_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Backend/Page.php';
require_once 'Zend/Validate.php';
require_once 'Ihush/Bpm/Flow.php';
require_once 'Ihush/Bpm/Action.php';
require_once 'Ihush/Bpm/Lang/Pbel.php';

/**
 * @package Ihush_App_Backend
 */
class RequestPage extends Ihush_App_Backend_Page
{
	public function __init ()
	{
		parent::__init();
		$this->authenticate();
		
		$this->pbel = new Ihush_Bpm_Lang_Pbel();
	}
	
	public function __done ()
	{
		
	}
	
	public function indexAction ()
	{
		$this->forward("sendList");
	}
	
	public function sendListAction ()
	{
		$bpmReqDao = $this->dao->load('Core_BpmRequest');
		$requestPage = $bpmReqDao->getSendByPage($this->admin['id']);
		$this->view->requestList = $requestPage['list'];
		$this->view->paging = $requestPage['page'];
		$this->render('request/sendlist.tpl');
	}
	
	public function recvListAction ()
	{
		$bpmReqDao = $this->dao->load('Core_BpmRequest');
		$this->view->requestList = $bpmReqDao->getRecvByPage($this->admin['id'], $this->admin['role']);
//		Hush_Util::dump($this->view->requestList);
		$this->render('request/recvlist.tpl');
	}
	
	public function selectFlowAction ()
	{
		$bpmFlowDao = $this->dao->load('Core_BpmFlow');
		
		$flowList = $bpmFlowDao->getByRole($this->admin['role']);
		
		$this->view->request = $_POST;
		$this->view->flowList = $flowList;
		$this->render('request/selectflow.tpl');
	}
	
	public function createAction ()
	{
		$flowId = $this->param('flowId');
		
		// get flow first node
		$bpmFlow = new Ihush_Bpm_Flow($flowId);
		$nodeId = $bpmFlow->gotoFirstNode()->getNodeId();
		
		// do post
		if ($_POST) {
			// init daos
			$bpmReqDao = $this->dao->load('Core_BpmRequest');
			// validation
			foreach ($_POST as $k => $v) {
				if (!Zend_Validate::is($v, 'NotEmpty')) {
					$this->addError('common.notempty', $k);
				}
			}
			// create request
			if ($this->noError()) {
				// 
				$modelData = $_POST;
				unset($modelData['bpm_request_subject']);
				// create request
				$reqData['author_id'] = $this->admin['id'];
				$reqData['bpm_flow_id'] = $flowId;
				$reqData['bpm_node_id'] = $nodeId;
				$reqData['bpm_request_subject'] = $this->param('bpm_request_subject');
				$reqData['bpm_request_body'] = json_encode($modelData);
				$reqData['bpm_request_sent'] = 1;
				$reqData['bpm_request_status'] = 0;
				$reqId = $bpmReqDao->create($reqData);
				// save operation
				$this->_saveOp($reqId, 'request::create');
				// jump to execute action
				$bpmFlow->gotoNextNode();
				$nextNodeId = $bpmFlow->getNodeId();;
				$this->forward("/request/execute/reqId/{$reqId}/flowId/{$flowId}/nodeId/{$nextNodeId}");
			}
		}
		
		// do display
		$this->view->request = $_POST;
		$this->view->modelform = $bpmFlow->setLang($this->pbel)->execute();
		if (!$this->view->modelform) $this->addError('bpm.flow.flowerr');
		$this->render('request/create.tpl');
	}
	
	public function executeAction ()
	{
		$reqId = $this->param('reqId');
		$flowId = $this->param('flowId');
		$nodeId = $this->param('nodeId');
		
		if ($reqId && $flowId && $nodeId) {
			// executive node code
			$bpmFlow = new Ihush_Bpm_Flow($flowId);
			$bpmFlow->gotoNode($nodeId);
			$bpmFlow->setLang($this->pbel)->execute();
			// update request info
			$bpmNodeStatus = (int) $bpmFlow->getNodeAttr();
			$bpmReqDao = $this->dao->load('Core_BpmRequest');
			$bpmReqDao->update(array(
				'bpm_node_id' => $nodeId,
				'bpm_request_id' => $reqId,
				'bpm_request_status' => $bpmNodeStatus
			));
		}
		
		// jump to index
		$this->forward("/request/" . $this->param('redir'));
	}
	
	public function viewAction ()
	{
		$reqId = $this->param('reqId');
		$bpmReqDao = $this->dao->load('Core_BpmRequest');
		$bpmReqOpDao = $this->dao->load('Core_BpmRequestOp');
		$this->view->request = $bpmReqDao->getDetails($reqId);
		$this->view->requestOp = $bpmReqOpDao->getByReqId($reqId);
		$this->render('request/view.tpl');
	}
	
	public function cancelAction ()
	{
		$reqId = $this->param('reqId');
		$bpmReqDao = $this->dao->load('Core_BpmRequest');
		
		// do post
		if ($_POST) {
			if ($reqId) {
				$requestData['bpm_request_id'] = $reqId;
				$requestData['bpm_request_status'] = 0; // deleted status
				$bpmReqDao->update($requestData);
			}
			$this->forward("/request/sendList");
		}
		
		$this->view->request = $bpmReqDao->getDetails($reqId);
		$this->render('request/cancel.tpl');
	}
	
	public function auditAction ()
	{
		$reqId = $this->param('reqId');
		$auditId = $this->param('auditId');
		
		$bpmReqDao = $this->dao->load('Core_BpmRequest');
		$bpmReqAuditDao = $this->dao->load('Core_BpmRequestAudit');
		
		$thisRequestData = $bpmReqDao->getDetails($reqId);
		$flowId = (int) $thisRequestData['bpm_flow_id'];
		$nodeId = (int) $thisRequestData['bpm_node_id'];
		
		// do post
		if ($_POST) {
			if ($reqId && $auditId) {
				// update bpm_request
				$requestData['bpm_request_id'] = $reqId;
				$requestData['bpm_request_comment'] = $this->param('bpm_request_comment');
				$bpmReqDao->update($requestData);
				// update bpm_request_audit
				$auditStatus = $this->param('bpm_request_audit_status');
				$auditData['bpm_request_audit_id'] = $auditId;
				$auditData['bpm_request_audit_done'] = 1;
				$auditData['bpm_request_audit_status'] = $auditStatus;
				$bpmReqAuditDao->update($auditData);
				// save operation
				$opAction = $auditStatus ? 'request::audit_pass' : 'request::audit_fail';
				$this->_saveOp($reqId, $opAction);
			}
			// jump to execute action
			$bpmFlow = new Ihush_Bpm_Flow($flowId);
			$bpmFlow->gotoNode($nodeId);
			$bpmFlow->gotoNextNode();
			$nextNodeId = $bpmFlow->getNodeId();;
			$this->forward("/request/execute/reqId/{$reqId}/flowId/{$flowId}/nodeId/{$nextNodeId}/redir/recvList");
		}
		
		$this->view->request = $thisRequestData;
		$this->render('request/audit.tpl');
	}
	
	private function _saveOp ($reqId, $action, $detail = '')
	{
		$bpmAction = new Ihush_Bpm_Action();
		$opData['user_id'] = $this->admin['id'];
		$opData['bpm_request_id'] = $reqId;
		$opData['bpm_request_op_action'] = $bpmAction->get($action);
		$opData['bpm_request_op_detail'] = '';
		$opData['bpm_request_op_time'] = time();
		
		$bpmReqOpDao = $this->dao->load('Core_BpmRequestOp');
		$bpmReqOpDao->create($opData);
	}
}