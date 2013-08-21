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
require_once 'Ihush/Bpm/Model.php';
require_once 'Ihush/Bpm/Node.php';
require_once 'Ihush/Bpm/Lang/Pbel.php';
require_once 'Ihush/App/Backend/Page/AclPage.php';

/**
 * @package Ihush_App_Backend
 */
class BpmPage extends AclPage
{
	public function __init ()
	{
		parent::__init(); 
		$this->authenticate();
		$this->view->cssList = array('css/bpm.css');
		$this->view->jsList = array('js/block.js', 'js/excanvas.js');
		
		$this->bpmModel = new Ihush_Bpm_Model();
		$this->bpmNode = new Ihush_Bpm_Node();
		$this->pbel = new Ihush_Bpm_Lang_Pbel();
	}
	
	public function indexAction () 
	{
		$this->forward('admin');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Bpm management actions
	
	public function adminAction () 
	{
		$bpmFlowDao = $this->dao->load('Core_BpmFlow');
		$this->view->bpmFlowList = $bpmFlowDao->getByPage();
		$this->render('bpm/admin/index.tpl');
	}
	
	public function adminAddBasicAction ()
	{
		// do post
		if ($_POST) {
			// init daos
			$bpmFlowDao = $this->dao->load('Core_BpmFlow');
			$bpmModelDao = $this->dao->load('Core_BpmModel');
			// validation
			if (!Zend_Validate::is($this->param('bpm_flow_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm flow name');
			}
			if ($this->noError()) {
				// do create bpm flow
				$flowData['bpm_flow_name'] = $this->param('bpm_flow_name');
				$flowData['bpm_flow_desc'] = $this->param('bpm_flow_desc');
				$flowData['bpm_flow_status'] = 0;
				$flowId = $bpmFlowDao->create($flowData);
				// do create bpm flow model
				$modelData['bpm_flow_id'] = $flowId;
				$modelId = $bpmModelDao->create($modelData);
				// jump to next step (adminEditBasic)
				$this->forward("/bpm/adminEditBasic/flowId/{$flowId}");
			}
		}
		
		// default data
		$this->view->flow = $_POST;
		$this->render('bpm/admin/add/basic.tpl');
	}
	
	public function adminEditBasicAction ()
	{
		$flowId = $this->param('flowId');
		
		$bpmFlowDao = $this->dao->load('Core_BpmFlow');
		
		// do post
		if ($_POST) {
			// merged roles
			$roles = $this->mergeRoles($this->param('roles_'), $this->param('roles'));
			// validation
			if (!Zend_Validate::is($this->param('bpm_flow_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm flow name');
			}
			if (!$roles) {
				$this->addError('common.notempty', 'Role list');
			}
			if ($this->noError()) {
				// do create bpm flow
				$flowData['bpm_flow_id'] = $flowId;
				$flowData['bpm_flow_name'] = $this->param('bpm_flow_name');
				$flowData['bpm_flow_desc'] = $this->param('bpm_flow_desc');
				$flowData['bpm_flow_status'] = $this->param('bpm_flow_status');
				$bpmFlowDao->update($flowData);
				$bpmFlowDao->updateRoles($flowId, $roles);
				// jump to list page
				$this->forward("/bpm/");
			}
		}
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		$this->view->selroles = $aclRoleDao->getRoleByFlowId($flowId, $this->getRoleIds($this->view->allroles));
		$this->view->oldroles = $this->buildRoles($this->filterOldRoles($this->view->selroles));
		
		// default data
		$flowData = $bpmFlowDao->read($flowId);
		$this->view->flow = $flowData;
		$this->render('bpm/admin/edit/basic.tpl');
	}
	
	public function adminSaveModelAction ()
	{
		$flowId = $this->param('flowId');
		
		$bpmFlowDao = $this->dao->load('Core_BpmFlow');
		$bpmModelDao = $this->dao->load('Core_BpmModel');
		$bpmModelFieldDao = $this->dao->load('Core_BpmModelField');
		
		$modelList = $bpmModelDao->getAllByFlowId($flowId);
		$modelData = isset($modelList[0]) ? $modelList[0] : array(); // get first model as default
		$modelId = $modelData['bpm_model_id'] ? $modelData['bpm_model_id'] : 0;
		
		// do post
		if ($_POST) {
			$modelNewData['bpm_model_id'] = $modelId;
			$modelNewData['bpm_model_form'] = $this->param('bpm_model_form');
			$bpmModelDao->update($modelNewData);
			// jump to edit page
			$this->forward("/bpm/adminEditBasic/flowId/{$flowId}");
		}
		
		$flowData = $bpmFlowDao->read($flowId);
		$fieldData = $bpmModelFieldDao->getByModelId($modelId);
		$fieldList = array();
		foreach ($fieldData as $field) {
			$feildId = $field['bpm_model_field_id'];
			$feildType = $field['bpm_model_field_type'];
			$feildName = $field['bpm_model_field_name'];
			$feildAttr = $field['bpm_model_field_attr'];
			$feildAlias = $field['bpm_model_field_alias'];
			$feildOption = $field['bpm_model_field_option'];
			array_push($fieldList, array(
				'id' => $feildId,
				'name' => $feildName,
				'form' => $this->bpmModel->convertToForm($feildType, "field[$feildId]", $feildAttr, $feildOption)
			));
		}
		
		$this->view->flow = $flowData;
		$this->view->model = $modelData;
		$this->view->fieldList = $fieldList;
		$this->render('bpm/admin/add/model.tpl');
	}
	
	public function adminSaveChartAction ()
	{		
		$flowId = $this->param('flowId');
		
		$bpmFlowDao = $this->dao->load('Core_BpmFlow');
		$bpmNodeDao = $this->dao->load('Core_BpmNode');
		$bpmNodePathDao = $this->dao->load('Core_BpmNodePath');
		
		// do post
		if ($_POST) {
			// jump to edit page
			$this->forward("/bpm/adminEditBasic/flowId/{$flowId}");
		}
		
		$flowData = $bpmFlowDao->read($flowId);
		$bpmNodeList = $bpmNodeDao->getAllByFlowId($flowId);
		$bpmPathList = $bpmNodePathDao->getAllByFlowId($flowId);
		
		$this->view->flow = $flowData;
		$this->view->nodeList = $bpmNodeList;
		$this->view->pathList = $bpmPathList;
		$this->render('bpm/admin/add/chart.tpl');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Bpm admin ajax & popup actions
	
	public function adminFieldAddAction ()
	{
		$modelId = $this->param('modelId');
		
		// do post
		if ($_POST) {
			// init daos
			$bpmModelFieldDao = $this->dao->load('Core_BpmModelField');
			// validation
			if (!Zend_Validate::is($this->param('bpm_model_field_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm model field name');
			}
			if (!Zend_Validate::is($this->param('bpm_model_field_type'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm model field type');
			} else {
				if ($this->param('bpm_model_field_length') && !Zend_Validate::is($this->param('bpm_model_field_length'), 'Digits')) {
					$this->addError('common.badformat', 'Bpm model field length');
				}
				if ($this->param('bpm_model_field_option') && preg_match('/^###/', $this->param('bpm_model_field_option'))) {
					$this->addError('common.badformat', 'Bpm model field option');
				}
			}
			if ($this->noError()) {
				// do create bpm flow
				$fieldData['bpm_model_id'] = $modelId;
				$fieldData['bpm_model_field_type'] = $this->param('bpm_model_field_type');
				$fieldData['bpm_model_field_name'] = $this->param('bpm_model_field_name');
				$fieldData['bpm_model_field_attr'] = $this->param('bpm_model_field_attr');
				$fieldData['bpm_model_field_length'] = $this->param('bpm_model_field_length') ? $this->param('bpm_model_field_length') : 0;
				$fieldData['bpm_model_field_option'] = $this->param('bpm_model_field_option') ? $this->param('bpm_model_field_option') : '';
				$bpmModelFieldDao->create($fieldData);
				$this->_closeOverlay();
			}
		}
		
		$this->view->field = $_POST;
		$this->view->modelId = $modelId;
		$this->view->fieldTypeOptions = $this->bpmModel->getFieldTypes();
		$this->render('bpm/admin/field/add.tpl');
	}
	
	public function adminFieldEditAction ()
	{
		$fieldId = $this->param('fieldId');
		
		$bpmModelFieldDao = $this->dao->load('Core_BpmModelField');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('bpm_model_field_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm model field name');
			}
			if (!Zend_Validate::is($this->param('bpm_model_field_type'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm model field type');
			} else {
				if ($this->param('bpm_model_field_length') && !Zend_Validate::is($this->param('bpm_model_field_length'), 'Digits')) {
					$this->addError('common.badformat', 'Bpm model field length');
				}
				if ($this->param('bpm_model_field_option') && preg_match('/^###/', $this->param('bpm_model_field_option'))) {
					$this->addError('common.badformat', 'Bpm model field option');
				}
			}
			if ($this->noError()) {
				// do create bpm flow
				$fieldData['bpm_model_field_id'] = $this->param('bpm_model_field_id');
				$fieldData['bpm_model_field_type'] = $this->param('bpm_model_field_type');
				$fieldData['bpm_model_field_name'] = $this->param('bpm_model_field_name');
				$fieldData['bpm_model_field_attr'] = $this->param('bpm_model_field_attr');
				$fieldData['bpm_model_field_length'] = $this->param('bpm_model_field_length') ? $this->param('bpm_model_field_length') : 0;
				$fieldData['bpm_model_field_option'] = $this->param('bpm_model_field_option') ? $this->param('bpm_model_field_option') : '';
				$bpmModelFieldDao->update($fieldData);
				$this->_closeOverlay();
			}
		}
		
		$this->view->field = ($_POST) ? $_POST : $bpmModelFieldDao->read($fieldId);
		$this->view->fieldTypeOptions = $this->bpmModel->getFieldTypes();
		$this->render('bpm/admin/field/edit.tpl');
	}
	
	public function adminNodeAddAction ()
	{
		$flowId = $this->param('flowId');
		$modelId = $this->param('modelId');
		
		// do post
		if ($_POST) {
			// init daos
			$bpmNodeDao = $this->dao->load('Core_BpmNode');
			// validation
			if (!Zend_Validate::is($this->param('bpm_node_attr'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node attr');
			}
			if (!Zend_Validate::is($this->param('bpm_node_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node name');
			}
			if (!Zend_Validate::is($this->param('bpm_node_type'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node type');
			}
			if ($this->noError()) {
				// do create bpm flow
				$nodeData['bpm_flow_id'] = $flowId;
				$nodeData['bpm_node_attr'] = $this->param('bpm_node_attr');
				$nodeData['bpm_node_name'] = $this->param('bpm_node_name');
				$nodeData['bpm_node_type'] = $this->param('bpm_node_type');
				$nodeData['bpm_node_pos_left'] = 10; // default position
				$nodeData['bpm_node_pos_top'] = 10; // default position
				$bpmNodeDao->create($nodeData);
				$this->_closeOverlay();
			}
		}
		
		$this->view->node = $_POST;
		$this->view->nodeOptions = $this->_getNodeOptions($flowId);
		$this->view->nodeTypeOptions = $this->bpmNode->getAllTypes();
		$this->view->nodeAttrOptions = $this->bpmNode->getAllAttrs();
		$this->render('bpm/admin/node/add.tpl');
	}
	
	public function adminNodeEditAction ()
	{
		$flowId = $this->param('flowId');
		$nodeId = $this->param('nodeId');
		
		// init daos
		$bpmNodeDao = $this->dao->load('Core_BpmNode');
		$bpmNodePathDao = $this->dao->load('Core_BpmNodePath');
		$bpmModelDao = $this->dao->load('Core_BpmModel');
		$roleDao = $this->dao->load('Core_Role');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('bpm_node_attr'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node attr');
			}
			if (!Zend_Validate::is($this->param('bpm_node_name'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node name');
			}
			if (!Zend_Validate::is($this->param('bpm_node_type'), 'NotEmpty')) {
				$this->addError('common.notempty', 'Bpm node type');
			}
			//
			$nextNodes = array();
			$bpmNodeCode = $this->param('bpm_node_code');
			if (!$this->pbel->prepare($bpmNodeCode)) {
				$this->addError('bpm.node.langerr');
			} else {
				$nextNodes = $this->pbel->getNextNodes($bpmNodeCode);
				if (!$bpmNodeDao->checkNodes($flowId, $nextNodes)) {
					$this->addError('bpm.node.nodeerr');
				}
			}
			// 
			if ($this->noError()) {
				// do create bpm flow
				$nodeData['bpm_node_id'] = $nodeId;
				$nodeData['bpm_node_attr'] = $this->param('bpm_node_attr');
				$nodeData['bpm_node_name'] = $this->param('bpm_node_name');
				$nodeData['bpm_node_type'] = $this->param('bpm_node_type');
				$nodeData['bpm_node_next'] = $this->param('bpm_node_next');
				$nodeData['bpm_node_code'] = $this->param('bpm_node_code') ? $this->param('bpm_node_code') : '';
				$bpmNodeDao->update($nodeData);
				// update bpm path
				if ($flowId && $nodeId) {
					// for common node
					if ($this->param('bpm_node_next')) {
						$pathData['bpm_flow_id'] = $flowId;
						$pathData['bpm_node_id_from'] = $nodeId;
						$pathData['bpm_node_id_to'] = $this->param('bpm_node_next');
						$bpmNodePathDao->replace($pathData);
					}
					// for judgement node
					if ($nextNodes) {
						foreach ($nextNodes as $nextNodeId) {
							$pathData['bpm_flow_id'] = $flowId;
							$pathData['bpm_node_id_from'] = $nodeId;
							$pathData['bpm_node_id_to'] = $nextNodeId;
							$bpmNodePathDao->replace($pathData);
						}
					}
				}
				$this->_closeOverlay();
			}
		}
		
		$this->view->node = ($_POST) ? $_POST : $bpmNodeDao->read($nodeId);
		$this->view->nodeOptions = $this->_getNodeOptions($flowId, $nodeId);
		$this->view->nodeTypeOptions = $this->bpmNode->getAllTypes();
		$this->view->nodeAttrOptions = $this->bpmNode->getAllAttrs();
		$this->view->modelList = $bpmModelDao->getFlowFieldList($flowId);
		$this->view->nodeList = $bpmNodeDao->getAllByFlowId($flowId);
		$this->view->roleList = $roleDao->getAllRoles();
		$this->view->pbelDocs = $this->pbel->getDocs();
		$this->render('bpm/admin/node/edit.tpl');
	}
	
	public function adminNodeDeleteAction ()
	{
		$nodeId = $this->param('nodeId');
		
		$bpmNodeDao = $this->dao->load('Core_BpmNode');
		
		if (!$nodeId) {
			$this->addError('common.notempty', 'Bpm node id');
		}
		if ($bpmNodeDao->canBeRemoved($nodeId)) {
			$this->addError('bpm.node.cannotremoved');
		}
		if ($this->noError()) {
			$bpmNodeDao->delete($nodeId);
			$this->_closeOverlay();
		}
		
		$this->render('bpm/admin/node/delete.tpl');
	}
	
	public function adminNodeUpdatePosAction ()
	{
		if ($this->param('nodeId') && $this->param('nodePosLeft') && $this->param('nodePosTop')) {
			$bpmNodeDao = $this->dao->load('Core_BpmNode');
			$nodePosData['bpm_node_id'] = $this->param('nodeId');
			$nodePosData['bpm_node_pos_left'] = $this->param('nodePosLeft');
			$nodePosData['bpm_node_pos_top'] = $this->param('nodePosTop');
			$bpmNodeDao->update($nodePosData);
		}
	}
	
	public function adminPathClearAction ()
	{
		if ($this->param('flowId')) {
			$bpmNodePathDao = $this->dao->load('Core_BpmNodePath');
			$bpmNodePathDao->delete($this->param('flowId'));
		}
		elseif ($this->param('nodeId')) {
			$bpmNodePathDao = $this->dao->load('Core_BpmNodePath');
			$bpmNodePathDao->delete($this->param('nodeId'), 'bpm_node_id_from');
		}
	}
	
	private function _getNodeOptions ($flowId, $exceptNodeId = 0)
	{
		$options = array();
		$bpmNodeDao = $this->dao->load('Core_BpmNode');
		$bpmNodeList = $bpmNodeDao->getAllByFlowId($flowId);
		foreach ($bpmNodeList as $node) {
			$nodeId = isset($node['bpm_node_id']) ? intval($node['bpm_node_id']) : 0;
			$nodeName = isset($node['bpm_node_name']) ? trim($node['bpm_node_name']) : '';
			if (!$nodeId || !$nodeName || $nodeId == $exceptNodeId) continue;
			$options[$node['bpm_node_id']] = $node['bpm_node_name'];
		}
		return $options;
	}
	
	private function _closeOverlay ()
	{
		echo '<script>parent.location.reload();</script>';
		exit;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Bpm actions
	
	
}