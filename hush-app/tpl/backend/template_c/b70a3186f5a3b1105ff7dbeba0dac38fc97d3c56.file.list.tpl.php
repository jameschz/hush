<?php /* Smarty version Smarty3-b8, created on 2013-07-19 16:33:02
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\acl/app/list.tpl" */ ?>
<?php /*%%SmartyHeaderCode:85151e8f9be245f87-12889782%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'b70a3186f5a3b1105ff7dbeba0dac38fc97d3c56' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\acl/app/list.tpl',
      1 => 1357552552,
    ),
  ),
  'nocache_hash' => '85151e8f9be245f87-12889782',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php $_template = new Smarty_Internal_Template("frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<div class="maintop">
<img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_arrow_right.png" class="icon" /> 菜单/应用 列表
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px">&nbsp;ID</th>
			<th align="left" style="width:200px">菜单名</th>
			<th align="left" style="width:100px">应用</th>
			<th align="left" style="width:200px">应用路径</th>
			<th align="left">角色权限</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		<?php  $_smarty_tpl->tpl_vars['app'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('appTree')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['app']->key => $_smarty_tpl->tpl_vars['app']->value){
?>
		<tr onclick="javascript:$('.menu<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
').toggle();">
			<td align="left"><?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
</td>
			<td align="left"><a href='appEdit?id=<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
'><u><?php echo $_smarty_tpl->getVariable('app')->value['name'];?>
</u></a><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/sort_asc.gif" /></td>
			<td align="left"></td>
			<td align="left"></td>
			<td align="left"><?php echo $_smarty_tpl->getVariable('app')->value['role'];?>
</td>
			<td align="right">
				<a href="appEdit?id=<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
">编辑</a>
				<?php if ($_smarty_tpl->getVariable('_admin')->value['name']==$_smarty_tpl->getVariable('_sa')->value){?> | <a href="javascript:$.form.confirm('appDel?id=<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
', '确认删除<?php if ($_smarty_tpl->getVariable('app')->value['is_app']=='YES'){?>应用<?php }else{ ?>菜单<?php }?>“<?php echo $_smarty_tpl->getVariable('app')->value['name'];?>
”？');">删除</a><?php }?>
			</td>
		</tr>
			<?php  $_smarty_tpl->tpl_vars['app1'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('app')->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['app1']->key => $_smarty_tpl->tpl_vars['app1']->value){
?>
			<tr class="menu<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
 disn">
				<td align="left"><?php echo $_smarty_tpl->getVariable('app1')->value['id'];?>
</td>
				<td align="left">└ <a href='appEdit?id=<?php echo $_smarty_tpl->getVariable('app1')->value['id'];?>
'><u><?php echo $_smarty_tpl->getVariable('app1')->value['name'];?>
</u></a></td>
				<td align="left"></td>
				<td align="left"></td>
				<td align="left"><?php echo $_smarty_tpl->getVariable('app1')->value['role'];?>
</td>
				<td align="right">
					<a href="appEdit?id=<?php echo $_smarty_tpl->getVariable('app1')->value['id'];?>
">编辑</a>
					<?php if ($_smarty_tpl->getVariable('_admin')->value['name']==$_smarty_tpl->getVariable('_sa')->value){?> | <a href="javascript:$.form.confirm('appDel?id=<?php echo $_smarty_tpl->getVariable('app1')->value['id'];?>
', '确认删除<?php if ($_smarty_tpl->getVariable('app')->value['is_app']=='YES'){?>应用<?php }else{ ?>菜单<?php }?>“<?php echo $_smarty_tpl->getVariable('app')->value['name'];?>
”？');">删除</a><?php }?>
				</td>
			</tr>
				<?php  $_smarty_tpl->tpl_vars['app2'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('app1')->value['list']; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['app2']->key => $_smarty_tpl->tpl_vars['app2']->value){
?>
				<tr class="menu<?php echo $_smarty_tpl->getVariable('app')->value['id'];?>
 disn">
					<td align="left"><?php echo $_smarty_tpl->getVariable('app2')->value['id'];?>
</td>
					<td align="left">└ └ <a href='appEdit?id=<?php echo $_smarty_tpl->getVariable('app2')->value['id'];?>
'><u><?php echo $_smarty_tpl->getVariable('app2')->value['name'];?>
</u></a></td>
					<td align="left"><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/icon_right.png" class="icon" /></td>
					<td align="left"><?php echo $_smarty_tpl->getVariable('app2')->value['path'];?>
</td>
					<td align="left"><?php echo $_smarty_tpl->getVariable('app2')->value['role'];?>
</td>
					<td align="right">
						<a href="appEdit?id=<?php echo $_smarty_tpl->getVariable('app2')->value['id'];?>
">编辑</a>
						<?php if ($_smarty_tpl->getVariable('_admin')->value['name']==$_smarty_tpl->getVariable('_sa')->value){?> | <a href="javascript:$.form.confirm('appDel?id=<?php echo $_smarty_tpl->getVariable('app2')->value['id'];?>
', '确认删除<?php if ($_smarty_tpl->getVariable('app')->value['is_app']=='YES'){?>应用<?php }else{ ?>菜单<?php }?>“<?php echo $_smarty_tpl->getVariable('app')->value['name'];?>
”？');">删除</a><?php }?>
					</td>
				</tr>
				<?php }} ?>
			<?php }} ?>
		<?php }} ?>
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='appadd';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>



</div>

<?php $_template = new Smarty_Internal_Template("frame/foot.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>
