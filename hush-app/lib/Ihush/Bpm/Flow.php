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
 
require_once 'Hush/Bpm/Flow.php';
require_once 'Ihush/Dao.php';

/**
 * @package Ihush_Bpm
 */
class Ihush_Bpm_Flow extends Hush_Bpm_Flow
{
	/**
	 * 
	 */
	protected $thisNode = null;
	
	/**
	 * 
	 */
	protected $thisNodeId = null;
	
	/**
	 * 
	 */
	protected $thisNodeType = null;
	
	/**
	 * 
	 */
	protected $thisNodeAttr = null;
	
	/**
	 * call by construct
	 */
	protected function _loadFromDb ($flowId)
	{
		$this->dao = new Ihush_Dao();
		
		// we need build flow path
		$bpmNodePathDao = $this->dao->load('Core_BpmNodePath');
		$pathList = $bpmNodePathDao->getAllByFlowId($flowId);
		foreach ((array) $pathList as $path) {
			$fromId = (int) $path['bpm_node_id_from'];
			$toId = (int) $path['bpm_node_id_to'];
			$this->path[$fromId] = $toId;
		}
	}
	
	/**
	 * Get current node
	 */
	public function getNode ()
	{
		return $this->thisNode;
	}
	
	/**
	 * Get current node id
	 */
	public function getNodeId ()
	{
		return $this->thisNodeId;
	}
	
	/**
	 * Get current node type
	 */
	public function getNodeType ()
	{
		return $this->thisNodeType;
	}
	
	/**
	 * Get current node attr
	 * This will effect request status
	 */
	public function getNodeAttr ()
	{
		return $this->thisNodeAttr;
	}
	
	/**
	 * Goto the specific node by id
	 * @param int $nodeId
	 */
	public function gotoNode ($nodeId)
	{
		$bpmNodeDao = $this->dao->load('Core_BpmNode');
		$this->thisNode = $bpmNodeDao->read($nodeId);
		$this->thisNodeId = (int) $this->thisNode['bpm_node_id'];
		$this->thisNodeType = (int) $this->thisNode['bpm_node_type'];
		$this->thisNodeAttr = (int) $this->thisNode['bpm_node_attr'];
		return $this;
	}
	
	/**
	 * Goto the first node of current flow
	 */
	public function gotoFirstNode ()
	{
		// get by flow id
		if ($this->getFlowId()) {
			$bpmNodeDao = $this->dao->load('Core_BpmNode');
			$this->thisNode = $bpmNodeDao->getFirstByFlowId($this->getFlowId());
			$this->thisNodeId = (int) $this->thisNode['bpm_node_id'];
			$this->thisNodeType = (int) $this->thisNode['bpm_node_type'];
			$this->thisNodeAttr = (int) $this->thisNode['bpm_node_attr'];
		}
		return $this;
	}
	
	/**
	 * Goto the next node
	 */
	public function gotoNextNode ()
	{
		if ($this->thisNodeId) {
			$nextNodeId = isset($this->path[$this->thisNodeId]) ? $this->path[$this->thisNodeId] : 0;
			if ($nextNodeId) $this->gotoNode($nextNodeId);
		}
		return $this;
	}
	
	/**
	 * Execute the code of current node
	 */
	public function execute ()
	{
		if ($this->lang && $this->thisNode) {
			return $this->lang->execute($this->thisNode['bpm_node_code']);
		}
	}
}