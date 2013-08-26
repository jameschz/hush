<?php
/**
 * Ihush Page
 *
 * @category   Ihush
 * @package    Ihush_App_Backend
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'Ihush/App/Backend/Page.php';

/**
 * @package Ihush_App_Backend
 */
class TestApi extends Ihush_App_Backend_Page
{
	/**
	 * ---------------------------------------------------------------------------------------------
	 * > 接口说明：测试接口1
	 * <code>
	 * URL地址：/remote/test/api1
	 * 提交方式：GET
	 * </code>
	 * ---------------------------------------------------------------------------------------------
	 * @title 测试接口1
	 * @action /remote/test/api1
	 * @method get
	 */
	public function api1Action () 
	{
		echo "this is test api1";
	}
	
	/**
	 * ---------------------------------------------------------------------------------------------
	 * > 接口说明：测试接口2
	 * <code>
	 * URL地址：/remote/test/api2
	 * 提交方式：GET
	 * </code>
	 * ---------------------------------------------------------------------------------------------
	 * @title 测试接口2
	 * @action /remote/test/api2
	 * @params key1 1 test key1
	 * @params key2 2 test key2
	 * @params key3 3 test key3
	 * @method get
	 */
	public function api2Action ()
	{
		echo "this is test api2\n";
		echo "\nGET data :\n";
		print_r($_GET);
		echo "\nPOST data :\n";
		print_r($_POST);
	}
	
	/**
	 * ---------------------------------------------------------------------------------------------
	 * > 接口说明：测试接口3
	 * <code>
	 * URL地址：/remote/test/api3
	 * 提交方式：POST
	 * </code>
	 * ---------------------------------------------------------------------------------------------
	 * @title 测试接口3
	 * @action /remote/test/api3
	 * @params key1 1 test key1
	 * @params key2 2 test key2
	 * @params key3 3 test key3
	 * @method post
	 */
	public function api3Action ()
	{
		echo "this is test api3\n";
		echo "\nGET data :\n";
		print_r($_GET);
		echo "\nPOST data :\n";
		print_r($_POST);
	}
}