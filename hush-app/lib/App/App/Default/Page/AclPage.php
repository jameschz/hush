<?php
/**
 * App Page
 *
 * @category   App
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'App/App/Default/Page.php';
require_once 'Zend/Validate.php';

/**
 * @package App_App_Default
 */
class AclPage extends App_App_Default_Page
{
	public function __init ()
	{
		parent::__init(); // overload parent class's method
		$this->authenticate();
	}
	
	public function __done ()
	{
		// Notice : max_role_size must be bigger than 5
// 		$this->view->max_role_size = max(count($this->view->allroles), count($this->view->selroles), 5);
		$this->view->max_role_size = 10;
	}
	
	public function welcomeAction ()
	{
        // TODO : welcome function
	}
	
	public function personalAction ()
	{
		$aclUserDao = $this->dao->load('Core_User');
		$userId = $this->admin['id'] ? $this->admin['id'] : 0;
		$user = $aclUserDao->read($this->admin['id']);
	
		// do post
		if ($_POST) {
			// validation
			if (!$userId) {
				$this->addError('common.err.notempty', 'User Id');
			}
// 			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
// 				$this->addError('common.err.notempty', 'User name');
// 			}
			if ($this->noError()) {
// 				$data['name'] = $this->param('name');
				if ($this->param('pass')) {
					$data['pass'] = Hush_Util::md5($this->param('pass'));
				}
				// do update
				if ($userId) {
					$aclUserDao->update($data, null, 'id=' . $userId);
					$this->addErrorMsg('Personal Infomation updated successfully');
				}
			}
		}
	
		$this->view->user = $user;
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// User actions
	
	public function userListAction () 
	{
		$aclUserDao = $this->dao->load('Core_User');
		$this->view->userList = $aclUserDao->getUserList();
		$this->render('acl/user/list.tpl');
	}
	
	public function userAddAction ()
	{
		$aclUserDao = $this->dao->load('Core_User');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'UserName');
			}
			if (!Zend_Validate::is($this->param('pass'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Password');
			}
			if (!$this->param('roles')) {
				$this->addError('common.err.notempty', 'Role list');
			}
			if ($this->noError()) {
				$dao = $this->dao->load('Core_User');
				if ($dao->read($this->param('name'), 'name')) {
					$this->addError('common.err.existed', 'UserName');
				}
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = $this->param('name');
				$data['pass'] = Hush_Util::md5($this->param('pass'));
				// do create
				$userId = $aclUserDao->create($data);
				if ($userId) {
					$aclUserDao->updateRoles($userId, $this->param('roles'));
				}
				$this->forward('userList');
			}
		}
		
		// default data
		$this->view->user = $_POST;
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		
		$this->render('acl/user/add.tpl');
	}
	
	public function userDelAction ()
	{
		if ($this->param('id')) {
			$aclUserDao = $this->dao->load('Core_User');
			$aclUserDao->delete($this->param('id'));
			$aclUserDao->updateRoles($this->param('id'));
		}
		$this->forward('userList');
	}
	
	public function userEditAction ()
	{
		$aclUserDao = $this->dao->load('Core_User');
		
		$user = $aclUserDao->read($this->param('id'));
		
		// do post
		if ($_POST) {
			// merged roles
			$roles = $this->mergeRoles($this->param('roles_'), $this->param('roles'));
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'User name');
			}
			if (!$roles) {
				$this->addError('common.err.notempty', 'Role list');
			}
			if ($this->noError()) {
				$dao = $this->dao->load('Core_User');
				$res = $dao->read($this->param('name'), 'name');
				if ($res) {
					if ($res['id'] != $this->param('id')) {
						$this->addError('common.err.existed', 'UserName');
					}
				}
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = $this->param('name');
				if ($this->param('pass')) {
					$data['pass'] = Hush_Util::md5($this->param('pass'));
				}
				// do update
				if ($this->param('id')) {
					$aclUserDao->update($data, null, 'id=' . $this->param('id'));
					$aclUserDao->updateRoles($this->param('id'), $roles);
					$this->forward('userList');
				}
			}
		}
		
		// default data
		$this->view->user = $user;
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		$this->view->selroles = $aclRoleDao->getRoleByUserId($this->param('id'), $this->getRoleIds($this->view->allroles));
		$this->view->oldroles = $this->buildRoles($this->filterOldRoles($this->view->selroles));
		$this->render('acl/user/edit.tpl');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Role actions
	
	public function roleListAction ()
	{
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->roleList = $aclRoleDao->getRoleList();
		$this->render('acl/role/list.tpl');
	}
	
	public function roleAddAction ()
	{
		$aclRoleDao = $this->dao->load('Core_Role');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Role name');
			}
			if (!Zend_Validate::is($this->param('alias'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Alias name');
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = strtoupper($this->param('name'));
				$data['alias'] = $this->param('alias');
				// do create
				$roleId = $aclRoleDao->create($data);
				if ($roleId) {
					$aclRoleDao->updatePrivs($roleId, $this->param('roles'));
				}
				$this->forward('roleList');
			}
		}
		
		// default data
		$this->view->role = $_POST;

		// fill role select box
		$this->view->allroles = $aclRoleDao->getAllRoles();

		$this->render('acl/role/add.tpl');
	}
	
	public function roleEditAction ()
	{
		$aclRoleDao = $this->dao->load('Core_Role');

		$role = $aclRoleDao->read($this->param('id'));
		
		// do post
		if ($_POST) {
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Role name');
			}
			if (!Zend_Validate::is($this->param('alias'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Alias name');
			}
			if ($this->noError()) {
				$data['name'] = strtoupper($this->param('name'));
				$data['alias'] = $this->param('alias');
				if ($this->param('id')) {
					$aclRoleDao->update($data, null, 'id='.$this->param('id'));
					$aclRoleDao->updatePrivs($this->param('id'), $this->param('privs'));
					$this->forward('roleList');
				}
			}
		}
		
		// default data
		$this->view->role = $role;
		
		// fill role select box
		$this->view->allroles = $aclRoleDao->getAllRoles();
		$this->view->selroles = $aclRoleDao->getPrivByRoleId($this->param('id'));
		
		$this->render('acl/role/edit.tpl');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Resource actions
	
	public function resourceListAction ()
	{
		$aclResDao = $this->dao->load('Core_Resource');
		$this->view->resourceList = $aclResDao->getResourceList();
		$this->render('acl/resource/list.tpl');
	}
	
	public function resourceAddAction ()
	{
		$aclResDao = $this->dao->load('Core_Resource');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Resource name');
			}
			if (!Zend_Validate::is($this->param('description'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'Resource description');
			}
			if (!$this->param('roles')) {
				$this->addError('common.err.notempty', 'Role list');
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = $this->param('name');
				$data['app_id'] = $this->param('app_id');
				$data['description'] = $this->param('description');
				// do create
				$resourceId = $aclResDao->create($data);
				if ($resourceId) {
					$aclResDao->updateRoles($resourceId, $this->param('roles'));
				}
				$this->forward('resourceList');
			}
		}
		
		// default data
		$this->view->resource = $_POST;
		
		// fill app select list
		$this->view->appopts = $this->getAppOpts();
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		
		$this->render('acl/resource/add.tpl');
	}
	
	public function resourceDelAction ()
	{
		if ($this->param('id')) {
			$aclResDao = $this->dao->load('Core_Resource');
			$aclResDao->delete($this->param('id'));
			$aclResDao->updateRoles($this->param('id'));
		}
		$this->forward('resourceList');
	}
	
	public function resourceEditAction ()
	{
		$aclResDao = $this->dao->load('Core_Resource');
		
		$user = $aclResDao->read($this->param('id'));
		
		// do post
		if ($_POST) {
			// merged roles
			$roles = $this->mergeRoles($this->param('roles_'), $this->param('roles'));
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'User name');
			}
			if (!$roles) {
				$this->addError('common.err.notempty', 'Role list');
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = $this->param('name');
				$data['app_id'] = $this->param('app_id');
				$data['description'] = $this->param('description');
				// do update
				if ($this->param('id')) {
					$aclResDao->update($data, null, 'id=' . $this->param('id'));
					$aclResDao->updateRoles($this->param('id'), $roles);
					$this->forward('resourceList');
				}
			}
		}
		
		// default data
		$this->view->resource = $user;
		
		// fill app select list
		$this->view->appopts = $this->getAppOpts();
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		$this->view->selroles = $aclRoleDao->getRoleByResourceId($this->param('id'), $this->getRoleIds($this->view->allroles));
		$this->view->oldroles = $this->buildRoles($this->filterOldRoles($this->view->selroles));
		
		$this->render('acl/resource/edit.tpl');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Menu actions
	
	public function appListAction ()
	{
		$appDao = $this->dao->load('Core_App');
		$this->view->appTree = $appDao->getAppTree();
//		Hush_Util::dump($this->view->appTree);
		$this->render('acl/app/list.tpl');
	}
	
	public function appAddAction ()
	{
		$appDao = $this->dao->load('Core_App');
		
		// do post
		if ($_POST) {
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'App / Menu name');
			}
			if (!Zend_Validate::is($this->param('pid'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'App / Menu level');
			}
			if (!$this->param('roles')) {
				$this->addError('common.err.notempty', 'Role list');
			}
			// check name
			if (!$appDao->checkName($this->param('name'))) {
				$this->addError('common.err.existed', 'App Name');
			}
			// check path
			if (!$appDao->checkPath($this->param('path'))) {
				$this->addError('common.err.existed', 'App path');
			}
			if ($this->noError()) {
				// prepare data
				$data['pid'] = $this->param('pid');
				$data['name'] = $this->param('name');
				$data['path'] = $this->param('path');
				$data['is_app'] = $this->param('is_app');
				// do create
				$appId = $appDao->create($data);
				if ($appId) {
					$appDao->updateRoles($appId, $this->param('roles'));
				}
				$this->forward('appList');
			}
		}
		
		// default data
		$this->view->app = $_POST;
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		
		$this->render('acl/app/add.tpl');
	}
	
	public function appDelAction ()
	{
		if ($this->param('id')) {
			$appDao = $this->dao->load('Core_App');
			$appDao->delete($this->param('id'));
			$appDao->updateRoles($this->param('id'));
		}
		$this->forward('appList');
	}
	
	public function appEditAction ()
	{
		$appDao = $this->dao->load('Core_App');
		
		$app = $appDao->read($this->param('id'));
		$app_parent = $appDao->read($app['pid']);
		
		// do post
		if ($_POST) {
			// merged roles
			$roles = $this->mergeRoles($this->param('roles_'), $this->param('roles'));
			// validation
			if (!Zend_Validate::is($this->param('name'), 'NotEmpty')) {
				$this->addError('common.err.notempty', 'App / Menu name');
			}
			if (!$roles) {
				$this->addError('common.err.notempty', 'Role list');
			}
			// check path
			if (!$appDao->checkPath($this->param('path'), $this->param('id'))) {
				$this->addError('common.err.existed', 'App path');
			}
			if ($this->noError()) {
				// prepare data
				$data['name'] = $this->param('name');
				$data['path'] = $this->param('path');
				$data['order'] = $this->param('order') ? $this->param('order') : 0;
				if ($this->param('pid')) {
					$data['pid'] = $this->param('pid');
				}
				// do update
				if ($this->param('id')) {
					$appDao->update($data, null, 'id=' . $this->param('id'));
					$appDao->updateRoles($this->param('id'), $roles);
					$this->forward('appList');
				}
			}
		}
		
		// default data
		$this->view->app = $app;
		$this->view->app_parent = $app_parent;
		
		// fill role select box
		$aclRoleDao = $this->dao->load('Core_Role');
		$this->view->allroles = $aclRoleDao->getAllPrivs($this->admin['role']);
		$this->view->selroles = $aclRoleDao->getRoleByAppId($this->param('id'), $this->getRoleIds($this->view->allroles));
		$this->view->oldroles = $this->buildRoles($this->filterOldRoles($this->view->selroles));
		
		$this->render('acl/app/edit.tpl');
	}
	
	public function appAjaxAction ()
	{
		// get ajax options
		$this->view->opt = $this->param('opt');
		$this->view->sel = $this->param('sel');
		
		switch ($this->param('opt')) {
			case 'menu' :
				$this->view->appopts = $this->getAppOpts(1);
				break;
			case 'app' : 
				$this->view->appopts = $this->getAppOpts(2);
				break;
		}
		
		$this->render('acl/app/ajax.tpl');
	}
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// Common methods
	
	protected function getAppOpts ($level = 0)
	{
		$appDao = $this->dao->load('Core_App');
		$appTreeList = $appDao->getAppTree();
		
//		Hush_Util::dump($appTreeList);
		
		switch ($level) {
			case 1 :
				$appTreeList = $this->makeOpt($appTreeList, 'id', 'name');
				break;
			case 2 :
				foreach ((array) $appTreeList as $topList) {
					if (!$topList['id']) continue;
					$menuOpts = $this->makeOpt($topList['list'], 'id', 'name');
					$appTreeList[$topList['id']]['list'] = $menuOpts;
				}
				break;
			case 3 :
			default :
				foreach ((array) $appTreeList as $topList) {
					if (!$topList['id']) continue;
					foreach ((array) $topList['list'] as $menuList) {
						if (!$menuList['id']) continue;
						$menuOpts = $this->makeOpt($menuList['list'], 'id', 'name');
						$appTreeList[$topList['id']]['list'][$menuList['id']]['list'] = $menuOpts;
					}
				}
				break;
		}
		
//		Hush_Util::dump($appTreeList);
		
		return $appTreeList;
	}
	
	protected function getRoleIds ($roles = array())
	{
		$role_ids = array();
		foreach ((array) $roles as $role) {
			if ($role['id']) $role_ids[] = $role['id'];
		}
		return $role_ids;
	}
	
	protected function filterOldRoles ($roles = array())
	{
		$role_ids = array();
		foreach ((array) $roles as $role) {
			if ($role['id'] && $role['readonly']) $role_ids[] = $role['id'];
		}
		return $role_ids;
	}
	
	protected function buildRoles ($roles = array())
	{
		return is_array($roles) ? implode(',', $roles) : $roles;
	}
	
	protected function mergeRoles ($old_roles, $new_roles) 
	{
		if (!is_array($old_roles)) $old_roles = explode(',', $old_roles);
		if (!is_array($new_roles)) $new_roles = explode(',', $new_roles);
		$merged = array_merge($old_roles, $new_roles);
		
		return array_filter($merged); // skip empty item
	}
}
