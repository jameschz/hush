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
			<td align="left"><a href='roleEdit?id={$role.id}'><u>{$role.name}</u></a></td>
			<td align="left">{$role.alias}</td>
			<td align="left">{$role.role}</td>
			<td align="right">
				<a href="roleEdit?id={$role.id}">编辑</a> | 删除
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='roleadd';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}