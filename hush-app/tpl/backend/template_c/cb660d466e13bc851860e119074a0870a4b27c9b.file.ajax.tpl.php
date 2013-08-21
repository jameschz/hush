<?php /* Smarty version Smarty3-b8, created on 2013-07-19 16:33:18
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\acl/app/ajax.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1228251e8f9ce936076-86936904%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'cb660d466e13bc851860e119074a0870a4b27c9b' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\acl/app/ajax.tpl',
      1 => 1357552552,
    ),
  ),
  'nocache_hash' => '1228251e8f9ce936076-86936904',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if (!is_callable('smarty_function_html_options')) include 'D:\workspace\phplibs\Smarty_3\plugins\function.html_options.php';
?><?php if ($_smarty_tpl->getVariable('opt')->value=='app'){?>
	<option value="0">-- 请选择所属菜单 --</option>
	<?php  $_smarty_tpl->tpl_vars['menus'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('appopts')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['menus']->key => $_smarty_tpl->tpl_vars['menus']->value){
?>
		<optgroup label="<?php echo $_smarty_tpl->getVariable('menus')->value['name'];?>
" title="">
			<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->getVariable('menus')->value['list'],'selected'=>$_smarty_tpl->getVariable('sel')->value),$_smarty_tpl->smarty,$_smarty_tpl);?>

		</optgroup>
	<?php }} ?>
<?php }elseif($_smarty_tpl->getVariable('opt')->value=='menu'){?>
	<option value="0">-- 顶级菜单 --</option>
	<?php echo smarty_function_html_options(array('options'=>$_smarty_tpl->getVariable('appopts')->value,'selected'=>$_smarty_tpl->getVariable('sel')->value),$_smarty_tpl->smarty,$_smarty_tpl);?>

<?php }?>