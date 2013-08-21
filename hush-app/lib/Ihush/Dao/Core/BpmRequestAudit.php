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

/**
 * @package Ihush_Dao_Core
 */
class Core_BpmRequestAudit extends Ihush_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'bpm_request_audit';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'bpm_request_audit_id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function isAudit ($reqId)
	{
		$sql = $this->select()
			->from($this->t1, array("count(1)"))
			->where("{$this->t1}.bpm_request_id = ? and {$this->t1}.bpm_request_audit_status = 0", $reqId);
		
		return ($this->dbr()->fetchOne($sql)) ? false : true;
	}
}