<?php
/**
 * Hush Framework
 *
 * @category   Hush
 * @package    Hush_Bpm
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */

/**
 * @package Hush_Bpm
 */
abstract class Hush_Bpm_Node
{
	/**
	 * @var int
	 */
	protected $id = 0;
	
	/**
	 * @var array
	 */
	private $types = array(
		1 => '任务节点',
		2 => '判断节点',
		3 => '跳转节点',
		4 => '审核节点',
	);
	
	/**
	 * @var array
	 */
	private $attrs = array(
		1 => '开始节点',
		2 => '中间节点',
		3 => '结束节点',
	);
	
	/**
	 * Empty Construct
	 */
	public function __construct ($nodeId = 0)
	{
		if ($nodeId) {
			$this->setNodeId($nodeId);
			$this->_loadFromDb($nodeId);
		}
	}
	
	/**
	 * Set current node id
	 */
	public function setNodeId ($id)
	{
		$this->id = $id;
		return $this;
	}
	
	/**
	 * Get current node id
	 */
	public function getNodeId ()
	{
		return $this->id;
	}
	
	/**
	 * Get all node types for building options
	 */
	public function getAllTypes ()
	{
		return $this->types;
	}
	
	/**
	 * Get all node attrs for building options
	 * Will influence request status
	 */
	public function getAllAttrs ()
	{
		return $this->attrs;
	}
	
	/**
	 * Load from database
	 * Should be implemented by subclasses
	 */
	abstract protected function _loadFromDb($nodeId);
}