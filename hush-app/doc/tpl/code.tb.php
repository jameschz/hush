<?php
/**
 * APPNAME Dao
 *
 * @category   APPNAME
 * @package    APPNAME_Dao_DBNAME
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'APPNAME/Dao/DBNAME.php';

/**
 * @package APPNAME_Dao_DBNAME
 */
class DBNAME_TABLENAME extends APPNAME_Dao_DBNAME
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'TABLENAME';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
}