{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 权限(ACL)资源列表
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
		{foreach $resourceList as $resource}
		<tr>
			<td align="left">{$resource.id}</td>
			<td align="left"><a href='resourceEdit?id={$resource.id}'><u>{$resource.name}</u></a></td>
			<td align="left">{if $resource.app_id eq '0'}<img src="{$_root}img/icon_right.png" class="icon" />{/if}</td>
			<td align="left">{$resource.description}</td>
			<td align="left">{$resource.role}</td>
			<td align="right">
				<a href="resourceEdit?id={$resource.id}">编辑</a>
				{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('resourceDel?id={$resource.id}', '确认删除权限资源“{$resource.name}”？');">删除</a>{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<button type="button" class="btn1s" onclick="javascript:location.href='resourceadd';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}