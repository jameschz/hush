<?php
/**
 * App Dao
 *
 * @category   App
 * @package    App_Dao_Demo
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/Dao/Demo.php';

/**
 * @package App_Dao_Demo
 */
class Demo_Test extends App_Dao_Demo
{
	/**
	 * @static
	 */
	const TABLE_NAME = 'test';
	
	/**
	 * @static
	 */
	const TABLE_PRIM = 'id';
	
	/**
	 * Initialize
	 */
	public function __init () 
	{
		$this->t1 = self::TABLE_NAME;
		$this->k1 = self::TABLE_PRIM;
		
		$this->_bindTable($this->t1, $this->k1);
	}
	
	public function page ($cond = array(), $fields = array(), $pageNum = 10, $orders = array())
	{
	    $fields = $fields ? $fields : array("*");
	    $sql = $this->select()->from($this->t1, $fields);
	    if (isset($cond['id']) && $cond['id']) $sql->where("id=?", $cond['id']);
	    if (isset($cond['type']) && $cond['type']>=0) $sql->where("type=?", $cond['type']);
	    if (isset($cond['title']) && $cond['title']) $sql->where("title like '%".$cond['title']."%'");
	    
	    // page code
	    require_once 'Core/Paging.php';
	    Core_Paging::$pageNum = $pageNum;
	    $page = Core_Paging::getInstance();
	    
	    // page code
	    $sql->limit($page->limitNum, $page->offsetNum);
	    
	    if ($orders) {
	        foreach ($orders as $order) $sql->order($order);
	    } else {
	        $sql->order("id desc");
	    }
	    
	    $res = $this->dbr()->fetchAll($sql);
	    
	    // page code
	    Core_Paging::simplePaging($page, $res);
	    
	    return array(
	        'page' => $page->toArray(),
	        'list' => $res,
	    );
	}
	
	/**
	 * search by condition
	 */
	public function search ($cond = array(), $fields = array(), $orders = array(), $limit = 0)
	{
	    $fields = $fields ? $fields : array("*");
	    $sql = $this->select()->from($this->t1, $fields);
	    if (isset($cond['id']) && $cond['id']) $sql->where("id=?", $cond['id']);
	    if (isset($cond['type']) && $cond['type']>=0) $sql->where("type=?", $cond['type']);
	    if (isset($cond['title']) && $cond['title']) $sql->where("title like '%".$cond['title']."%'");
	    
	    // limit sql
	    if ($limit) $sql->limit($limit);
	    
	    if ($orders) {
	        foreach ($orders as $order) $sql->order($order);
	    } else {
	        $sql->order("id desc");
	    }
	    
	    $res = $this->dbr()->fetchAll($sql);
	    
	    return $res;
	}
	
	/**
	 * 获取最大的ID
	 * @return int
	 */
	public function getMaxId ()
	{
	    $sql = $this->select()->from($this->t1, 'max(id)');
	    $res = $this->dbr()->fetchOne($sql);
	    return intval($res);
	}
	
	/**
	 * 事务测试逻辑
	 */
	public function testTrans1 ($id)
	{
	    $this->beginTransaction();
	    try {
	        $res = $this->update(array(
	            'id' => $id,
	            'title' => "trans 1 success : update {$id} !!!",
	            'dtime' => time(),
	        ));
	        if (!$res) {
	            // 此方式可以用于实现乐观锁，如果更新失败则抛错回滚
	            throw new Exception("trans update error : update {$id} !!!");
	        }
	        $this->commit();
	    } catch (Exception $e) {
	        $this->rollBack();
	        throw new Exception($e->getMessage());
	    }
	}
	
	/**
	 * 事务嵌套测试逻辑
	 */
	public function testTrans2 ($id)
	{
	    $this->beginTransaction();
	    try {
	        // 嵌套事务逻辑（也可以是其他库）
	        App_Dao::load('Demo_Test')->testTrans1($id);
	        // 当前事务逻辑
	        $id++;
	        $res = $this->update(array(
	            'id' => $id,
	            'title' => "trans 2 success : update {$id} !!!",
	            'dtime' => time(),
	        ));
	        if (!$res) {
	            // 此方式可以用于实现乐观锁，如果更新失败则抛错回滚
	            throw new Exception("trans update error : update {$id} !!!");
	        }
	        $this->commit();
	    } catch (Exception $e) {
	        $this->rollBack();
	        throw new Exception($e->getMessage());
	    }
	}
}