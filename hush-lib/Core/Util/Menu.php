<?php
require_once 'Core/Util.php';

class Core_Util_Menu
{
	public $mid = 0;
	public $apps = array();
	public $roles = array();
	public $menus = array();
	public $scopes = array();
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// 角色分配
	
	public $_roles = array(
		array(1, 'SA', '超管'),
		array(2, 'AM', '普管'),
		array(3, 'FI', '财务'),
		array(4, 'OP', '运营'),
	    array(5, 'MA', '市场'),
		array(6, 'BD', '商务'),
		array(7, 'CS', '客服'),
		array(8, 'ED', '编辑'),
		array(100, 'CH', '渠道'),
	);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// 角色分组

	public $grp_all = array(1,2,3,4,5,6,7,8,100);
	public $grp_sup = array(1,2);
	public $grp_vip = array(1);
	
	////////////////////////////////////////////////////////////////////////////////////////////////
	// 菜单方法
	
	public function setMenus ()
	{
		$this->menus = array(
		    // 在每个项目的 App_Util_Menu 类里设置
        );
	}
	
	public function setScope ($scope = 'core')
	{
		$this->scopes = (array) explode(',', $scope);
		return $this;
	}
	
	public function initMenu ($pid = 0, $children = array())
	{
		$this->setMenus();
		$menuList = $children ? $children : (array) $this->menus;
		if ($menuList) {
    		foreach ((array) $menuList as $menus) {
    			// 过滤scope
    			if (!in_array($menus['scope'], $this->scopes)) {
    				continue;
    			}
    			// 菜单id递增
    			$this->mid++;
    			$menu_icon = isset($menus['icon']) ? $menus['icon'] : '';
    			$menu_name = isset($menus['name']) ? $menus['name'] : '';
    			$menu_path = isset($menus['path']) ? $menus['path'] : '';
    			if (isset($menus['children'])) {
    				// 针对菜单
    				$this->apps[] = array(
    					'id' => $this->mid,
    					'icon' => $menu_icon,
    					'name' => $menu_name,
    					'path' => '',
    					'pid' => $pid,
    					'order' => 0,
    					'is_app' => 'NO',
    				);
    				foreach ($menus['roles'] as $roleId) {
    					$this->roles[] = array(
    						'app_id' => $this->mid,
    						'role_id' => $roleId,
    					);
    				}
    				// 递归调用
    				$this->initMenu($this->mid, $menus['children']);
    			} else {
    				// 针对app
    				$this->apps[] = array(
    					'id' => $this->mid,
    					'icon' => $menu_icon,
    					'name' => $menu_name,
    					'path' => $menu_path,
    					'pid' => $pid,
    					'order' => 0,
    					'is_app' => 'YES',
    				);
    				foreach ($menus['roles'] as $roleId) {
    					$this->roles[] = array(
    						'app_id' => $this->mid,
    						'role_id' => $roleId,
    					);
    				}
    			}
    		}
		}
		return $this;
	}
	
	public function getMenu ($path)
	{
	    $this->setMenus();
	    $menuList = (array) $this->menus;
	    if ($menuList) {
	        $menuId = 0;
	        $menuLv1Id = 0;
	        $menuLv2Id = 0;
	        $menuLv3Id = 0;
// 	        Core_Util::dump($menuList);exit;
	        foreach ($menuList as $menuLv1) {
	            $menuLv1Id = ++$menuId;
	            foreach ($menuLv1['children'] as $menuLv2) {
	                $menuLv2Id = ++$menuId;
	                foreach ($menuLv2['children'] as $menuLv3) {
	                    $menuLv3Id = ++$menuId;
	                    if ($path == $menuLv3['path']) {
	                        $menuLv1Name = "menu_lv1_{$menuLv1Id}";
	                        $menuLv2Name = "menu_lv2_{$menuLv2Id}";
	                        $menuLv3Name = "menu_lv3_{$menuLv3Id}";
	                        return array($menuLv1Name, $menuLv2Name, $menuLv3Name);
	                    }
	                }
	            }
	        }
	    }
	    return false;
	}
	
	public function writeDb ()
	{
	    // 更新角色表
	    if ($this->_roles) {
    		$dao_r = App_Dao::load('Core_Role');
    		$dao_r->dbw()->query("truncate ".$dao_r->table());
    		if ($this->_roles) {
    			$dao_r->dbw()->insertMultiRow($dao_r->table(), 
    				array('id', 'name', 'alias'), 
    				$this->_roles);
    		}
	    }
		// 更新应用表
	    if ($this->apps) {
    		$dao_a = App_Dao::load('Core_App');
    		$dao_a->dbw()->query("truncate ".$dao_a->table());
    		foreach ($this->apps as $data) {
    			$dao_a->create($data);
    		}
	    }
		// 更新应用角色权限
	    if ($this->roles) {
    		$dao_ar = App_Dao::load('Core_AppRole');
    		$dao_ar->dbw()->query("truncate ".$dao_ar->table());
    		foreach ($this->roles as $data) {
    			$dao_ar->create($data);
    		}
	    }
		return $this;
	}
}
