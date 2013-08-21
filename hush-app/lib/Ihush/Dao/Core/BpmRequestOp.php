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

/**
 * @package Ihush_Dao_Core
 */
class Core_BpmRequestOp extends Ihush_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_request_op';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_request_op_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		$this->t2 = Core_User::TABLE_NAME;
		$this->k2 = Core_User::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function getByReqId ($reqId)
	{
		$sql = $this->select()
			->from($this->t1, array("{$this->t1}.*", "{$this->t2}.name as user_name"))
			->join($this->t2, "{$this->t1}.user_id = {$this->t2}.{$this->k2}", null)
			->where("{$this->t1}.bpm_request_id = ?", $reqId);
		
		return $this->dbr()->fetchAll($sql);
	}
}