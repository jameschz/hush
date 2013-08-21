<?php /* Smarty version Smarty3-b8, created on 2013-07-19 16:33:24
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\acl/app/add.tpl" */ ?>
<?php /*%%SmartyHeaderCode:901651e8f9d448d710-32075701%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '2fe29b2dfa98c28ebcf612333c81965d8760a6db' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\acl/app/add.tpl',
      1 => 1357552552,
    ),
  ),
  'nocache_hash' => '901651e8f9d448d710-32075701',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<div class="maintop">
<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 新增 菜单/应用 信息
</div>

<div class="mainbox">

<?php $_template = new Smarty_Internal_Template("frame/error.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<form method="post">
<table class="titem" >
	<tr>
		<td class="field">创建类型</td>
		<td class="value">
			<input type="radio" name="is_app" value="NO" <?php if (!$_smarty_tpl->getVariable('app')->value['is_app']||$_smarty_tpl->getVariable('app')->value['is_app']=='NO'){?>checked<?php }?> /> 创建菜单 &nbsp;
			<input type="radio" name="is_app" value="YES" <?php if ($_smarty_tpl->getVariable('app')->value['is_app']=='YES'){?>checked<?php }?> /> 创建应用
		</td>
	</tr>
	<tr>
		<td class="field">显示层次 *</td>
		<td class="value">
			<select id="sel_pid" class="common" name="pid"></select>
		</td>
	</tr>
	<tr>
		<td class="field">显示名称 *</td>
		<td class="value"><input class="common" type="text" name="name" value="<?php echo $_smarty_tpl->getVariable('app')->value['name'];?>
" /></td>
	</tr>
	<tr>
		<td class="field">角色选择 *</td>
		<td class="value">
			<?php $_template = new Smarty_Internal_Template("acl/forms/roles_add.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>

		</td>
	</tr>
	<tr id="sel_path" class="disn">
		<td class="field">应用路径 *</td>
		<td class="value"><input class="common" type="text" name="path" value="<?php echo $_smarty_tpl->getVariable('app')->value['path'];?>
" /></td>
	</tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="submit" value="提交" />
			<input type="button" value="返回" onclick="javascript:history.go(-1);" />
		</td>
	</tr>
</table>
</form>

<script type="text/javascript">
// only when element exists
if ($("#sel_pid").length > 0) {
	var default_opt = '<?php if ($_smarty_tpl->getVariable('app')->value['is_app']=='YES'){?>app<?php }else{ ?>menu<?php }?>';
	// default create menu
	$.post('appAjax', { opt : default_opt , sel : '<?php echo $_smarty_tpl->getVariable('app')->value['pid'];?>
' }, function(text){
		$("#sel_pid").append(text);
	});
	// level option list
	$('form input[name=is_app]').click(function(){
		$("#sel_pid").empty();
		var opt = $(this).val() == 'YES' ? 'app' : 'menu';
		$.post('appAjax', { opt : opt , sel : '<?php echo $_smarty_tpl->getVariable('app')->value['pid'];?>
' }, function(text){
			$("#sel_pid").append(text);
		})
		if ($(this).val() == 'YES') {
			$("#sel_path").show();
		} else {
			$("#sel_path").hide();
		}
	});
}
</script>

</div>

<?php $_template = new Smarty_Internal_Template("frame/foot.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
