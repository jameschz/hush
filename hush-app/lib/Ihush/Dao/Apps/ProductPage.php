<?php
/**
 * Ihush Dao
 *
 * @category   Ihush
 * @package    Ihush_Dao_Apps
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/Dao/Apps.php';

/**
 * @package Ihush_Dao_Apps
 */
class Apps_ProductPage extends Ihush_Dao_Apps
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'product_p';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		
		$this->_bindTable($this->t1);
	}
	
	/**
	 * Paging Product List
	 */
	public function getListByPage ($each = 5)
	{
		$sql_count = $this->select();
		$sql_list = clone $sql_count; // used to build $sql_list
	
		$sql_count = $sql_count->from($this->t1, array("count(1) as total"));
		$sql_list = $sql_list->from($this->t1, array("{$this->t1}.*"))
			->limitPage(Hush_Util::param('p'), $each);
	
		$res_total = $this->dbr()->fetchOne($sql_count);
		$res_list = $this->dbr()->fetchAll($sql_list);
		
		require_once 'Hush/Paging.php';
		$paging = new Hush_Paging($res_total, $each, null, array(
// 			'Href' => '/testDb/mysqlPage/p/{page}?debug=sql',
			'Mode' => 2,
		));
	
		return array(
			'page' => $paging->toArray(),
			'list' => $res_list,
		);
	}
}