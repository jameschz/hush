<?php
/**
 * iHush Track
 *
 * @category   Track
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'AdminPage.php';

/**
 * @package App_App_Default
 */
class IndexPage extends AdminPage
{	
	public function __init ()
	{
		parent::__init();
	}
	
	public function indexAction () 
	{
		// 获取菜单列表
		$appDao = $this->dao->load('Core_App');
		$appList = $appDao->getAppListByRole($this->admin['role']);
		$this->view->appList = $appList;
	}
	
	public function msgnumAction ()
	{
	    // 获取消息个数
	    ajax(0, '', array('msg_num' => 0));
	}
}