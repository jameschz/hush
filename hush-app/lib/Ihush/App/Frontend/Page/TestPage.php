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
require_once 'Ihush/Paging.php';

/**
 * @package Ihush_App_Frontend
 */
class TestPage extends Ihush_App_Frontend_Page
{
	
	public function indexAction () 
	{
		echo '<b>This is index action</b>'; 
	}
	
	public function mappingAction () 
	{
		echo '<b>This is mapping action</b>'; 
	}
	
	public function staticAction ()
	{
		echo '<b>Static parameters : </b><br/><br/>';
		echo '<b>$1 : </b>' . $this->param('$1') . '<br/>';
		echo '<b>$2 : </b>' . $this->param('$2') . '<br/>';
	}
	
	public function pagingAction () 
	{
		echo '<b>Paging object : </b>';
		$this->pagingDemo();
		
	}
	
	/*
	 * 分页使用“特别说明”：
	 * 
	 * 本框架的分页支持三种方式：
	 * 1、构造函数的第一个参数是数组：针对普通数组的分页，也就是本例，如果需要分页的数据是现成的话，建议使用这种简单方式
	 * 2、构造函数的第一个参数是数字：常在 DAO 类中使用，参数表示查询出的总数，然后可使用分页类中的 frNum 和 toNum 数
	 * 值结合 MySQL 的 limit 使用。举例如下：
	 * ...
	 * $sql = $this->select()... // 查找语句
	 * $page = new Ihush_Paging($totalNumber, $eachPageNumber, $thisPageNumber, array(...));
	 * $paging = $page->paging(); // 进行分页，获取分页结果数组
	 * $sql->limit($paging['frNum'], $paging['toNum']); // 结合 Limit 分页
	 * ...
	 * 当然如果你要使用 Zend Db 自带的 limitPage 方法也是可以的，具体的实例见：
	 * hush-app/lib/Ihush/Dao/Core/BpmRequest.php 中的 getSendByPage() 方法的用法
	 * 3、构造函数的第一个参数为空：这种方式经常用在后台数据量爆大的情况，由于不需要传入 count 总数，也避免了大数量的情况下
	 * 出现的count的效率问题，大家可以灵活运用
	 */
	private function pagingDemo ()
	{
		$data = array();
		for ($i = 0; $i < 100; $i++) {
			$data[$i]['id'] = $i;
			$data[$i]['name'] = 'Test' . $i;
		}
		
		/*
		 * 分页类使用说明：
		 * 
		 * 参数1：具体用法见本方法前面的“特别说明”
		 * 参数2：每页包含的数据项个数
		 * 参数3：页码数，空则表示首页
		 * 参数4：分页模式，目前支持的 Mode 有三种，分别是 Google、Common、JavaEye 的分页模式
		 * 
		 * 更多使用方法请参考 hush-lib/Hush/Paging.php 类中的使用说明
		 */
		$page = new Ihush_Paging($data, 5, null, array(
			'Href' => '/test/p/{page}?debug=time',
			'Mode' => 3,
		));
		
		/*
		 * 打印数组形式：
		 * 
		 * 此数组可以提供给 Smarty 等模板直接展示，非常方便！
		 * 其中较常被展示的就是 totalPage（总页数）、prevStr（上页字串）、nextStr（下页字串）、pageStr（分页字串）
		 */
		Hush_Util::dump($page->toArray());
	}
	
	public function apiv1Action ()
	{
		echo '<b>This is api v1</b><br/><br/>';
		echo '<b>$_SERVER[\'REQUEST_URI\'] : </b>' . $_SERVER['REQUEST_URI'] . '<br/>';
	}
	
	public function apiv2Action ()
	{
		echo '<b>This is api v2</b><br/><br/>';
		echo '<b>$_SERVER[\'REQUEST_URI\'] : </b>' . $_SERVER['REQUEST_URI'] . '<br/>';
	}
}
