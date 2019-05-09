{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 菜单/应用 列表
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
		{foreach $appTree as $app}
		<tr onclick="javascript:$('.menu{$app.id}').toggle();">
			<td align="left" style="width:5%;">{$app.id}</td>
			<td align="left">{$app.name}<img src="{$_root}img/sort_asc.gif" /></td>
			<td align="left"></td>
			<td align="left"></td>
			<td align="left" style="width:30%;word-break:break-all">{$app.role}</td>
			<td align="right">
				{if $_acl->isAllowed($_admin.role, 'acl_app_edit')}
				<a href="appEdit?id={$app.id}">编辑</a>
				{else}编辑{/if}
				{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app.name}”？');">删除</a>{/if}
			</td>
		</tr>
			{foreach $app.list as $app1}
			<tr class="menu{$app.id}">
				<td align="left" style="width:5%;">{$app1.id}</td>
				<td align="left">└　{$app1.name}</td>
				<td align="left"></td>
				<td align="left"></td>
				<td align="left" style="width:30%;word-break:break-all">{$app1.role}</td>
				<td align="right">
					{if $_acl->isAllowed($_admin.role, 'acl_app_edit')}
					<a href="appEdit?id={$app1.id}">编辑</a>
					{else}编辑{/if}
					{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app1.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app1.name}”？');">删除</a>{/if}
				</td>
			</tr>
				{foreach $app1.list as $app2}
				<tr class="menu{$app.id}">
					<td align="left" style="width:5%;">{$app2.id}</td>
					<td align="left">└　└　{$app2.name}</td>
					<td align="left"><img src="{$_root}img/icon_right.png" class="icon" /></td>
					<td align="left" style="width:15%;word-break:break-all">{$app2.path}</td>
					<td align="left" style="width:30%;word-break:break-all">{$app2.role}</td>
					<td align="right">
						{if $_acl->isAllowed($_admin.role, 'acl_app_edit')}
						<a href="appEdit?id={$app2.id}">编辑</a>
						{else}编辑{/if}
						{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app2.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app2.name}”？');">删除</a>{/if}
					</td>
				</tr>
				{/foreach}
			{/foreach}
		{/foreach}
	</tbody>
	{if $_acl->isAllowed($_admin.role, 'acl_app_add')}
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='appadd';">新增</button>
			</td>
		</tr>
	</tfoot>
	{/if}
</table>

{*include file="frame/page.tpl"*}

</div>

{include file="frame/foot.tpl"}