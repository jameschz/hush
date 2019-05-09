<?php
/* escape error for pdt */
require_once 'Pdt/Libs.php';

/**
 * App Dao
 *
 * @category   App
 * @package    App_Dao
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/Dao.php';

/**
 * @package App_Dao
 */
class App_Dao_Demo extends App_Dao
{
	/**
	 * @static
	 */
	const DB_NAME = 'hush_demo';
	
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