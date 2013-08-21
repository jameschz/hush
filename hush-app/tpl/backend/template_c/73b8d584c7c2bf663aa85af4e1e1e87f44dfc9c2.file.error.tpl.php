<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:49:09
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\frame/error.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2485351773a1535dd26-21527472%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '73b8d584c7c2bf663aa85af4e1e1e87f44dfc9c2' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\frame/error.tpl',
      1 => 1357552553,
    ),
  ),
  'nocache_hash' => '2485351773a1535dd26-21527472',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('errors')->value){?>
	<div class="errorbox">
	<?php  $_smarty_tpl->tpl_vars['error'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('errors')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['error']->key => $_smarty_tpl->tpl_vars['error']->value){
?>
		<span><b>!</b> <?php echo $_smarty_tpl->getVariable('error')->value;?>
</span>
	<?php }} ?>
	</div>
<?php }?>