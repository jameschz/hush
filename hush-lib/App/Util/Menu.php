<?php
require_once 'Core/Util/Menu.php';

class App_Util_Menu extends Core_Util_Menu
{
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // 角色分配（重写）
    
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
    // 角色分组（重写）
    
    public $grp_all = array(1,2,3,4,5,6,7,8,100);
    public $grp_sup = array(1,2);
    public $grp_vip = array(1);
    
    ////////////////////////////////////////////////////////////////////////////////////////////////
    // 菜单方法（重写）
    
    public function setMenus ()
    {
        $this->menus = array(
            // 超级后台
            array('name' => '系统管理', 'roles' => $this->grp_all, 'scope' => 'core', 'children' => array(
                array('name' => '基础管理', 'icon' => 'icon icon-display', 'roles' => $this->grp_all, 'scope' => 'core', 'children' => array(
                    array('name' => '系统概览', 'roles' => $this->grp_all, 'scope' => 'core', 'path' => '/acl/welcome'),
                    array('name' => '个人设置', 'roles' => $this->grp_all, 'scope' => 'core', 'path' => '/acl/personal'),
                )),
                array('name' => '权限管理', 'icon' => 'icon icon-key', 'roles' => $this->grp_sup, 'scope' => 'core', 'children' => array(
                    array('name' => '角色管理', 'roles' => $this->grp_vip, 'scope' => 'core', 'path' => '/acl/roleList'),
                    array('name' => '用户管理', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/acl/userList'),
                    array('name' => '资源管理', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/acl/resourceList'),
                    array('name' => '菜单管理', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/acl/appList'),
                )),
            )),
            array('name' => '常用范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'children' => array(
                array('name' => '后台范例', 'icon' => 'icon icon-cog', 'roles' => $this->grp_sup, 'scope' => 'core', 'children' => array(
                    array('name' => '布局页面范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/home'),
                    array('name' => '列表页面范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/list'),
                    array('name' => '标签页面范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/tabs'),
                    array('name' => '页面切换范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/jump'),
                )),
                array('name' => '统计范例', 'icon' => 'icon icon-cog', 'roles' => $this->grp_sup, 'scope' => 'core', 'children' => array(
                    array('name' => '折线图范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/stats1'),
                    array('name' => '时线图范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/stats2'),
                    array('name' => '柱状图范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/stats3'),
                    array('name' => '饼状图范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/stats4'),
                )),
                array('name' => '程序范例', 'icon' => 'icon icon-cog', 'roles' => $this->grp_sup, 'scope' => 'core', 'children' => array(
                    array('name' => '数据库使用范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/code1'),
                    array('name' => '缓存使用范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/code2'),
                    array('name' => '会话使用范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/code3'),
                    array('name' => '日志使用范例', 'roles' => $this->grp_sup, 'scope' => 'core', 'path' => '/demo/code4'),
                )),
            )),
            array('name' => '测试工具', 'roles' => array(1), 'scope' => 'core', 'children' => array(
                array('name' => '测试菜单', 'icon' => 'icon icon-binoculars', 'roles' => array(1), 'scope' => 'core', 'children' => array(
                    array('name' => '基础接口测试', 'roles' => array(1), 'scope' => 'core', 'path' => '/test/apilist'),
                )),
            )),
        );
    }
}
