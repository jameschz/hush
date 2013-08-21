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
			<td align="left">{$app.id}</td>
			<td align="left"><a href='appEdit?id={$app.id}'><u>{$app.name}</u></a><img src="{$_root}img/sort_asc.gif" /></td>
			<td align="left"></td>
			<td align="left"></td>
			<td align="left">{$app.role}</td>
			<td align="right">
				<a href="appEdit?id={$app.id}">编辑</a>
				{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app.name}”？');">删除</a>{/if}
			</td>
		</tr>
			{foreach $app.list as $app1}
			<tr class="menu{$app.id} disn">
				<td align="left">{$app1.id}</td>
				<td align="left">└ <a href='appEdit?id={$app1.id}'><u>{$app1.name}</u></a></td>
				<td align="left"></td>
				<td align="left"></td>
				<td align="left">{$app1.role}</td>
				<td align="right">
					<a href="appEdit?id={$app1.id}">编辑</a>
					{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app1.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app.name}”？');">删除</a>{/if}
				</td>
			</tr>
				{foreach $app1.list as $app2}
				<tr class="menu{$app.id} disn">
					<td align="left">{$app2.id}</td>
					<td align="left">└ └ <a href='appEdit?id={$app2.id}'><u>{$app2.name}</u></a></td>
					<td align="left"><img src="{$_root}img/icon_right.png" class="icon" /></td>
					<td align="left">{$app2.path}</td>
					<td align="left">{$app2.role}</td>
					<td align="right">
						<a href="appEdit?id={$app2.id}">编辑</a>
						{if $_admin.name eq $_sa} | <a href="javascript:$.form.confirm('appDel?id={$app2.id}', '确认删除{if $app.is_app eq 'YES'}应用{else}菜单{/if}“{$app.name}”？');">删除</a>{/if}
					</td>
				</tr>
				{/foreach}
			{/foreach}
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='appadd';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>

{*include file="frame/page.tpl"*}

</div>

{include file="frame/foot.tpl"}