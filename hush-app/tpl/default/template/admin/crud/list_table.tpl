{if $aps.topmsg}
<div class="topmsg">{$aps.topmsg}</div>
{/if}
<table class="tlist" >
	<thead>
		<tr class="title">
			{foreach $field as $k => $v}
			{include file="admin/crud/list_th.tpl"}
			{/foreach}
			<th align="right"></th>
		</tr>
	</thead>
	<tbody>
		{foreach $result as $item}
		<tr>
			{foreach $field as $k => $v}
			{include file="admin/crud/list_td.tpl"}
			{/foreach}
			<td align="right" {if $aps.style.td_op}style="{$aps.style.td_op}"{/if}>
				<a href="javascript:openWindow('{$aps.action}{$aps.options.link_info}{if $aps.options.link_info|strpos:'?'}&{else}?{/if}{$aps.pkey}={$item[$aps.pkey]}','{$title}{$aps.options.name_info}');">{$aps.options.name_info}</a>
				{if $blocks && "edit"|in_array:$blocks}
                    {if $item._unclick && "edit"|in_array:$item._unclick}
                    | {$aps.options.name_edit}
                    {else}
				    | <a href="javascript:openWindow('{$aps.action}{$aps.options.link_edit}{if $aps.options.link_edit|strpos:'?'}&{else}?{/if}{$aps.pkey}={$item[$aps.pkey]}','{$title}{$aps.options.name_edit}');">{$aps.options.name_edit}</a>
                    {/if}
				{/if}
				{if $blocks && "verify"|in_array:$blocks}
                    {if $item._unclick && "verify"|in_array:$item._unclick}
                    | {$aps.options.name_verify}
                    {else}
				    | <a href="javascript:openWindow('{$aps.action}{$aps.options.link_verify}{if $aps.options.link_verify|strpos:'?'}&{else}?{/if}{$aps.pkey}={$item[$aps.pkey]}','{$title}{$aps.options.name_verify}');">{$aps.options.name_verify}</a> 
				    {/if}
                {/if}
				{if $blocks && "delete"|in_array:$blocks}
                    {if $item._unclick && "delete"|in_array:$item._unclick}
                    | {$aps.options.name_delete}
                    {else}
				    | <a href="javascript:if(confirm('确认{$aps.options.name_delete}{$title}？'))location.href='{$aps.action}{$aps.options.link_delete}?{$aps.pkey}={$item[$aps.pkey]}';">{$aps.options.name_delete}</a> 
				    {/if}
                {/if}
				{if $aps.extend}
				{foreach $aps.extend as $extend}
                    {if $item._unclick && $extend.name|in_array:$item._unclick}
                        | {$extend.name}
                    {else}
    					{if $extend.path}
    					| <a href="javascript:openWindow('{$extend.path}{if $extend.path|strpos:'?'}&{else}?{/if}{foreach $extend.params as $pk => $pv}{$pk}={$item[$pv]}&{/foreach}{$aps.pkey}={$item[$aps.pkey]}','{$title} > {$extend.name}');">{$extend.name}</a>
    					{elseif $extend.href}
    					| <a href="{$extend.href}{if $extend.href|strpos:'?'}&{else}?{/if}{foreach $extend.params as $pk => $pv}{$pk}={$item[$pv]}&{/foreach}{$aps.pkey}={$item[$aps.pkey]}">{$extend.name}</a>
    					{else}
                        | <a href="{$extend.link|replace:'{pval}':$item[$aps.pkey]}">{$extend.name}</a>
                        {/if}
                    {/if}
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

<div class="tside">
    <div style="padding-top:5px;">
    {include file="frame/page.tpl"}
    </div>
</div>

<script type="text/javascript">
$(function(){
	$('.icon_image').click(function(){
		var data_src = $(this).attr('src');
		var player = '<img src="'+data_src+'" style="width:100%;" />';
		layer.open({
			type: 1,
			title: '',
			closeBtn: 0,
			shadeClose: true,
			content: player
		});
	});
	$('.icon_audio').click(function(){
		var data_src = $(this).attr('data-src');
		var player = '<video src="'+data_src+'" controls="controls" style="width:500px;height:auto;"></video>';
		layer.open({
			type: 1,
			title: false,
			closeBtn: 0,
			shadeClose: true,
            area: 'auto',
            maxWidth: 500,
            content: player
		});
	});
	$('.icon_video').click(function(){
		var data_src = $(this).attr('data-src');
		var player = '<video src="'+data_src+'" controls="controls" style="width:500px;height:auto;"></video>';
		layer.open({
			type: 1,
			title: false,
			closeBtn: 0,
			shadeClose: true,
			area: 'auto',
			maxWidth: 500,
			content: player
		});
	});
});
</script>