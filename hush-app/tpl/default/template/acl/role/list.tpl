{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 后台角色列表
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px;">&nbsp;ID</th>
			<th align="left">角色名</th>
			<th align="left">角色别名</th>
			<th align="left">可操作角色</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $roleList as $role}
		<tr>
			<td align="left">{$role.id}</td>
			<td align="left">{$role.name}</td>
			<td align="left">{$role.alias}</td>
			<td align="left">{$role.role}</td>
			<td align="right">
				{if $_acl->isAllowed($_admin.role, 'acl_role_edit')}
				<a href="roleEdit?id={$role.id}">编辑</a>
				{else}编辑{/if}
				{if $_admin.name eq $_sa} | 
				删除
				{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	{if $_acl->isAllowed($_admin.role, 'acl_role_add')}
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='roleadd';">新增</button>
			</td>
		</tr>
	</tfoot>
	{/if}
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}