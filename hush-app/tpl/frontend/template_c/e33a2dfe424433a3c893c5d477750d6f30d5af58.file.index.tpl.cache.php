<?php /* Smarty version Smarty3-b8, created on 2013-08-13 17:50:35
         compiled from "D:\workspace\hush-framework\hush-app\tpl\frontend\template\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:29248520a016b74a113-21794609%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'e33a2dfe424433a3c893c5d477750d6f30d5af58' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\frontend\\template\\index\\index.tpl',
      1 => 1357552551,
    ),
  ),
  'nocache_hash' => '29248520a016b74a113-21794609',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


	<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/logo.gif" />
	
	<div style="padding:8px 0px 8px 0px"><hr/></div>
	
	<h1 style="font-size:14pt"><?php echo $_smarty_tpl->getVariable('welcome')->value;?>
</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> ZendFramework 和 Smarty 的完美结合（MVC）</li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 优化的 ZendFramework Url Mapping 机制（比 ZF 快 N 倍）</li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 完善的 Full Stack 前后台框架结构（带调试框架）</li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 支持数据库连接池，负载均衡，分库分表策略</li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 强大的 RBAC 权限控制系统（可扩展）</li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 整合 BPM 流程管理框架（可编程） </li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 松耦合，易安装，易扩展</li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Framework Performance Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 路由模式 : <a href="/?debug=time">执行时间</a></li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 传统模式 : <a href="/app/index.php?debug=time">执行时间</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Url Mapping Engine Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_round.png" class="icon" /> 普通映射 : <a href="/test/mapping?debug=time">/test/mapping</a></li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_round.png" class="icon" /> 分页演示 : <a href="/test/p/1?debug=time">/test/p/*</a></li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_round.png" class="icon" /> 模糊匹配 : <a href="/test/*?debug=time">/test/*</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Sharding Database Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_round.png" class="icon" /> DB Mysql : <a href="/testDb/mysqlShard?debug=sql">/testDb/mysqlShard</a></li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_round.png" class="icon" /> DB Mongo : <a href="/testDb/mongoShard?debug=time">/testDb/mongoShard</a></li>
	</ul>

<?php $_template = new Smarty_Internal_Template("frame/foot.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, 9999, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
