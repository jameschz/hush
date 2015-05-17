<?php
/**
 * Ihush App
 *
 * @category   Ihush
 * @package    Ihush_App_Frontend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Frontend/Page.php';

/**
 * @package Ihush_App_Frontend
 */
class TestDbPage extends Ihush_App_Frontend_Page
{
	public function __init ()
	{
		// init dao
		parent::__init();
	}
	
	public function indexAction () 
	{
		echo 'This is index action'; 
	}
	
	public function mysqlPageAction ()
	{
		$dao = $this->dao->load('Apps_ProductPage');
		$res = $dao->getListByPage(3);
		Hush_Util::dump($res);
	}
	
	public function mysqlTransactionAction ()
	{
		$dao = $this->dao->load('Apps_ProductPage');
		$dbw = $dao->dbw(); // get dbw for transaction
		$dbw->beginTransaction();
		try {
			// do update
			$dao->update(array(
				'id' => 1,
				'name' => 'Test product N',
			));
			// throw Exception for test
			throw new Exception('Test Exception');
			// commit
			$dbw->commit();
			echo 'Transaction Commited OK';
		} catch (Exception $e) {
			// rollback
			$dbw->rollback();
			echo 'Transaction Error : ' . $e->getMessage();
		}
	}
	
	public function mysqlShardAction () 
	{
		$dao = $this->dao->load('Apps_Product');
		
		if ($_POST) {
			$pid = $this->param('pid');
			$pname = $this->param('pname');
			$pdesc = $this->param('pdesc');
			try {
				switch ($this->param('act')) {
					case "Create":
						$this->view->res = $dao->shard($pid)->create(array(
							'id' => $pid,
							'name' => $pname,
							'desc' => $pdesc,
						));
						break;
					case "Read":
						$this->view->res = $dao->shard($pid)->read($pid);
						$this->view->sid = $this->view->res['id'];
						break;
					case "Update":
						$this->view->res = $dao->shard($pid)->update(array(
								'id' => $pid,
								'name' => $pname,
								'desc' => $pdesc,
						));
						break;
					case "Delete":
						$this->view->res = $dao->shard($pid)->delete($pid);
						break;
				}
			} catch (Exception $e) {
				$this->view->res = $e->getMessage();
			}
			$this->view->act = $this->param('act');
			$this->view->res = json_encode($this->view->res);
		}
		
		// get real time data
		$dbr = $dao->dbr(1,0); // assign db
		$sql = $dbr->select()->from('product_0'); // assign table
		$this->view->product_all_0 = $dbr->fetchAll($sql);
		
		$dbr = $dao->dbr(1,0); // assign db
		$sql = $dbr->select()->from('product_1'); // assign table
		$this->view->product_all_1 = $dbr->fetchAll($sql);
		
		$dbr = $dao->dbr(1,0); // assign db
		$sql = $dbr->select()->from('product_2'); // assign table
		$this->view->product_all_2 = $dbr->fetchAll($sql);
		
		$dbr = $dao->dbr(1,0); // assign db
		$sql = $dbr->select()->from('product_3'); // assign table
		$this->view->product_all_3 = $dbr->fetchAll($sql);
	}
	
	public function mongoShardAction () 
	{
		// init mongo db
		require_once 'Ihush/Mongo.php';
		$this->mongo = new Ihush_Mongo();
		$mongo = $this->mongo->load('Foo_Foo');
		
		// test create
		echo "<b>TEST CREATE :</b>";
		$result = $mongo->create(array('foo' => 1, 'val' => 1, '_time' => time()));
		Hush_Util::dump($result);
		usleep(10000);
		$result = $mongo->read(array('foo' => 1));
		Hush_Util::dump(iterator_to_array($result));
		usleep(10000);
		
		// test update
		echo "<b>TEST UPDATE :</b>";
		$result = $mongo->update(array('foo' => 1), array('foo' => 1, 'val' => 2, '_time' => time()));
		Hush_Util::dump($result);
		usleep(10000);
		$result = $mongo->read(array('foo' => 1));
		Hush_Util::dump(iterator_to_array($result));
		usleep(10000);
		
		// test delete
		echo "<b>TEST DELETE :</b>";
		$result = $mongo->delete(array('foo' => 1));
		Hush_Util::dump($result);
		usleep(10000);
		$result = $mongo->read(array('foo' => 1));
		Hush_Util::dump(iterator_to_array($result));
		ob_flush();
		flush();
	}
}
