{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> {$title}
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px">&nbsp;ID</th>
			<th align="left" style="width:200px">类别名称</th>
			<th align="left" style="width:100px">类别代号</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $tree as $node}
		<tr onclick="javascript:$('.menu{$node.id}').toggle();">
			<td align="left">{$node.id}</td>
			<td align="left"><a href="javascript:openWindow('{$bps.action}/a/edit?id={$node.id}','修改{$title}');"><u>{$node.name}</u></a><img src="{$_root}img/sort_asc.gif" /></td>
			<td align="left">{$node.code}</td>
			<td align="right">
				<a href="javascript:openWindow('{$bps.action}/a/edit?id={$node.id}','修改{$title}');">编辑</a> | 
				<a href="javascript:$.form.confirm('appDel?id={$node.id}', '确认删除“{$node.name}”？');">删除</a>
			</td>
		</tr>
			{foreach $node.list as $node1}
			<tr class="menu{$node.id}">
				<td align="left">{$node1.id}</td>
				<td align="left">└ <a href="javascript:openWindow('{$bps.action}/a/edit?id={$node1.id}','修改{$title}');"><u>{$node1.name}</u></a></td>
				<td align="left">{$node1.code}</td>
				<td align="right">
					<a href="javascript:openWindow('{$bps.action}/a/edit?id={$node1.id}','修改{$title}');">编辑</a> | 
					<a href="javascript:$.form.confirm('appDel?id={$node1.id}', '确认删除“{$node1.name}”？');">删除</a>
				</td>
			</tr>
			{/foreach}
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:openWindow('{$bps.action}/a/add');">新增{$title}</button>
			</td>
		</tr>
	</tfoot>
</table>

</div>

{include file="frame/window.tpl"}
{include file="frame/foot.tpl"}