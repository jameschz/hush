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

/**
 * @package App_Dao_Core
 */
class Core_UserRole extends App_Dao_Core
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'user_role';
	
	/**
	 * Initialize
	 */
	public function __init ()
	{
		$this->t1 = self::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
}