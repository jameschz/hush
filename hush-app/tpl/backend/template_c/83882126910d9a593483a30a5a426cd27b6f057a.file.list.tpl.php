<?php /* Smarty version Smarty3-b8, created on 2013-07-19 16:32:57
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\acl/resource/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1762151e8f9b9ac16e6-10289811%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '83882126910d9a593483a30a5a426cd27b6f057a' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\acl/resource/list.tpl',
      1 => 1357552552,
    ),
  ),
  'nocache_hash' => '1762151e8f9b9ac16e6-10289811',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<div class="maintop">
<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 权限(ACL)资源列表
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px;">&nbsp;ID</th>
			<th align="left">资源名</th>
			<th align="left">全局资源</th>
			<th align="left">资源说明</th>
			<th align="left">角色权限</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		<?php  $_smarty_tpl->tpl_vars['resource'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('resourceList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['resource']->key => $_smarty_tpl->tpl_vars['resource']->value){
?>
		<tr>
			<td align="left"><?php echo $_smarty_tpl->getVariable('resource')->value['id'];?>
</td>
			<td align="left"><a href='resourceEdit?id=<?php echo $_smarty_tpl->getVariable('resource')->value['id'];?>
'><u><?php echo $_smarty_tpl->getVariable('resource')->value['name'];?>
</u></a></td>
			<td align="left"><?php if ($_smarty_tpl->getVariable('resource')->value['app_id']=='0'){?><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_right.png" class="icon" /><?php }?></td>
			<td align="left"><?php echo $_smarty_tpl->getVariable('resource')->value['description'];?>
</td>
			<td align="left"><?php echo $_smarty_tpl->getVariable('resource')->value['role'];?>
</td>
			<td align="right">
				<a href="resourceEdit?id=<?php echo $_smarty_tpl->getVariable('resource')->value['id'];?>
">编辑</a>
				<?php if ($_smarty_tpl->getVariable('_admin')->value['name']==$_smarty_tpl->getVariable('_sa')->value){?> | <a href="javascript:$.form.confirm('resourceDel?id=<?php echo $_smarty_tpl->getVariable('resource')->value['id'];?>
', '确认删除权限资源“<?php echo $_smarty_tpl->getVariable('resource')->value['name'];?>
”？');">删除</a><?php }?>
			</td>
		</tr>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<button type="button" class="btn1s" onclick="javascript:location.href='resourceadd';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>

<?php $_template = new Smarty_Internal_Template("frame/page.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


</div>

<?php $_template = new Smarty_Internal_Template("frame/foot.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
