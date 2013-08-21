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
 
require_once 'Hush/Bpm/Action.php';
require_once 'Ihush/Bpm/Exception.php';

/**
 * @package Ihush_Bpm
 */
class Ihush_Bpm_Action extends Hush_Bpm_Action
{
	/**
	 * @var array
	 */
	private $actions = array(
		'flow::create'			=> '创建流程',
		'flow::update'			=> '更新流程',
		'flow::update_model'	=> '更新模块',
		'flow::update_node'		=> '更新节点',
		'request::create'		=> '创建申请',
		'request::audit_pass'	=> '通过申请',
		'request::audit_fail'	=> '拒绝申请',
	);
	
	/**
	 * Implement parent class's method
	 */
	public function get ($action)
	{
		if (!isset($this->actions[$action])) {
			throw new Ihush_Bpm_Exception('undefined bpm action');
		}
		return $this->actions[$action];
	} 
}