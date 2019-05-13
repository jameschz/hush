{include file="frame/head.tpl"}
{assign var=blocks value=","|explode:$blocks}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> {$title}列表
</div>

<div class="mainbox">

<form method="get" id="search_form">
{foreach $smarty.get as $k => $v}
{if !$bps.filter.$k}<input type="hidden" name="{$k}" value="{$v}" />{/if}
{/foreach}
<div style="padding:10px;border:1px solid #ccc">
	<table style="width:100%">
		<tr><td>
		{foreach $filter as $k => $v}
			{if $v.type eq 'text'}
			<input type="text" name="{$k}" value="{if $search.$k}{$search.$k}{/if}" placeholder="请输入{$v.name}" style="margin-right:10px;" />
			{elseif $v.type eq 'select'}
			<select class="search_selector" name="{$k}" style="width:100px;">
				{foreach $field.$k.data as $k1 => $v1}
				<option value="{$k1}" {if $search.$k eq $k1}selected{/if}>{$v1}</option>
				{/foreach}
			</select>
			{/if}
			&nbsp;
		{/foreach}
		<input type="submit" value="查找{$title}" />
		</td>
		<td style="text-align:right">
		{if "add"|in_array:$blocks}
		<input type="button" value="添加{$title}" onclick="javascript:openWindow('{$bps.action}/a/add');" />
		{/if}
		</td>
		</tr>
	</table>
</div>
</form>
{if $bps.topmsg}
<div class="topmsg">{$bps.topmsg}</div>
{/if}
<table class="tlist" >
	<thead>
		<tr class="title">
			{foreach $field as $k => $v}
			{include file="base/crud/list_th.tpl"}
			{/foreach}
			<th align="right"></th>
		</tr>
	</thead>
	<tbody>
		{foreach $result as $item}
		<tr>
			{foreach $field as $k => $v}
			{include file="base/crud/list_td.tpl"}
			{/foreach}
			<td align="right">
				<a href="{$bps.action}/a/info?{$bps.pkey}={$item[$bps.pkey]}&_back={$smarty.server.REQUEST_URI|urlencode}">详情</a>
				{if "edit"|in_array:$blocks}
				| <a href="{$bps.action}/a/edit?{$bps.pkey}={$item[$bps.pkey]}&_back={$smarty.server.REQUEST_URI|urlencode}">修改</a> 
				{/if}
				{if "verify"|in_array:$blocks}
				| <a href="{$bps.action}/a/verify?{$bps.pkey}={$item[$bps.pkey]}&_back={$smarty.server.REQUEST_URI|urlencode}">审核</a> 
				{/if}
				{if $bps.extend}
				{foreach $bps.extend as $extend}
				| <a href="{$extend.path}?{$bps.pkey}={$item[$bps.pkey]}&_back={$smarty.server.REQUEST_URI|urlencode}">{$extend.name}</a>
				{/foreach}
				{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="4"></td>
		</tr>
	</tfoot>
</table>

{include file="frame/page.tpl"}

</div>

<script type="text/javascript">
$(function(){
	$('.search_selector').change(function(){
		$('#search_form').submit();
	});
});
</script>

{include file="frame/window.tpl"}
{include file="frame/foot.tpl"}