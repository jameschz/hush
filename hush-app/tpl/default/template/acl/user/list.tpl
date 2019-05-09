{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 后台用户列表
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px;">&nbsp;ID</th>
			<th align="left" style="width:300px;">用户名&nbsp;<input type="text" class="filter_input" /></th>
			<th align="left">所属角色列表</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $userList as $user}
		<tr class="filter_rows">
			<td align="left">{$user.id}</td>
			<td align="left"><span class="filter_name">{$user.name}</span></td>
			<td align="left">{$user.role}</td>
			<td align="right">
				{if $_acl->isAllowed($_admin.role, 'acl_user_edit')}
				<a href="userEdit?id={$user.id}">编辑</a>
				{else}编辑{/if}
				{if $_admin.name eq $_sa} | 
				{if $user.name eq $_sa}删除{else}<a href="javascript:$.form.confirm('userDel?id={$user.id}', '确认删除用户“{$user.name}”？');">删除</a>{/if}
				{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	{if $_acl->isAllowed($_admin.role, 'acl_user_add')}
	<tfoot>
		<tr>
			<td colspan="4">
				<button type="button" class="btn1s" onclick="javascript:location.href='useradd';">新增</button>
			</td>
		</tr>
	</tfoot>
	{/if}
</table>

{include file="frame/page.tpl"}

<script type="text/javascript">
$.form.filter({
	filter_input : $('.filter_input'),
	filter_rows : $('.filter_rows'),
	filter_val : $('.filter_name')
});
</script>

</div>

{include file="frame/foot.tpl"}