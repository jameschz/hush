<?php /* Smarty version Smarty3-b8, created on 2013-07-19 16:33:24
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\acl/forms/roles_add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1614551e8f9d45cd1c7-73094302%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '51f82f5043e59763a3d51176e90b3f2129b40358' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\acl/forms/roles_add.tpl',
      1 => 1357552552,
    ),
  ),
  'nocache_hash' => '1614551e8f9d45cd1c7-73094302',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('allroles')->value){?>
<table>
	<tr>
		<td>
			<select multiple id="all_role" size="<?php echo $_smarty_tpl->getVariable('max_role_size')->value;?>
" style="width:139px;">
			<?php  $_smarty_tpl->tpl_vars['option'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('allroles')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['option']->key => $_smarty_tpl->tpl_vars['option']->value){
?>
				<option value="<?php echo $_smarty_tpl->getVariable('option')->value['id'];?>
"><?php echo $_smarty_tpl->getVariable('option')->value['name'];?>
 (<?php echo $_smarty_tpl->getVariable('option')->value['alias'];?>
)</option>
			<?php }} ?>
			</select>
		</td>
		<td>
			<input id="role_add" type="button" value=" &gt; " style="width:20px;" /><br/>
			<input id="role_del" type="button" value=" &lt; " style="width:20px;" />
		</td>
		<td>
			<select multiple id="sel_role" name="roles[]" size="<?php echo $_smarty_tpl->getVariable('max_role_size')->value;?>
" style="width:139px;"></select>
		</td>
	</tr>
</table>
<?php }else{ ?>
<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_warn.png" class="icon" /> 您没有任何角色的选择权限！
<?php }?>

<script type="text/javascript">
// set add & remove options
$('#role_add').click(function(){
	$('#all_role option:selected').each(function(){
		if ($("#sel_role option:contains('"+$(this).text()+"')").length < 1) {
			$('#sel_role').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		}
	});
	$('#all_role option:selected').each(function(){
		$(this).attr('selected', false);
	});
});
// remove select categories
$('#role_del').click(function(){
	$('#sel_role option:selected').each(function(){
		$(this).remove();
	});
});
// select all roles
$('form').submit(function(){
	$('#sel_role option').attr('selected', true);
	return true;
});
</script>