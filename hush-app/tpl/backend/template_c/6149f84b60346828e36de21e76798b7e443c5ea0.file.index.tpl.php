<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:49:16
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\common\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1099851773a1ca963f8-45631771%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '6149f84b60346828e36de21e76798b7e443c5ea0' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\common\\index.tpl',
      1 => 1357552553,
    ),
  ),
  'nocache_hash' => '1099851773a1ca963f8-45631771',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_modifier_date_format')) include 'D:\workspace\phplibs\Smarty_3\plugins\modifier.date_format.php';
?><?php $_template = new Smarty_Internal_Template("frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<div class="maintop">
<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 欢迎页面
</div>

<div class="mainbox">
	
	<h1 style="font-size:14pt">欢迎来到 Hush Framework 后台系统</h1>
	
	<ul style="margin-top:10px">
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 您的角色：
		<?php  $_smarty_tpl->tpl_vars['priv'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('_admin')->value['priv']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['priv']->key => $_smarty_tpl->tpl_vars['priv']->value){
?><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_man.png" class="icon" /> <?php echo $_smarty_tpl->getVariable('priv')->value;?>
 <?php }} ?></li>
		<li><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_star.png" class="icon" /> 系统时间：<?php echo smarty_modifier_date_format(time(),'%Y-%m-%d %H:%M:%S');?>
</li>
	</ul>

</div>

<?php $_template = new Smarty_Internal_Template("frame/foot.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
