<?php
/**
 * APPNAME Dao
 *
 * @category   APPNAME
 * @package    APPNAME_Dao
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'APPNAME/Dao.php';

/**
 * @package APPNAME_Dao
 */
class APPNAME_Dao_DBNAME extends APPNAME_Dao
{
	/**
	 * @static
	 */
	const DB_NAME = 'DBNAME';
	
	/**
	 * Construct
	 */
	public function __construct ()
	{
		// initialize dao
		parent::__construct(MysqlConfig::getInstance());
		
		// set default dao settings
		$this->_bindDb(self::DB_NAME);
	}
}