<form method="get" id="search_form">
{foreach $smarty.get as $k => $v}
{if $k ne 'p' && !$bps.filter.$k}<input type="hidden" name="{$k}" value="{$v}" />{/if}
{/foreach}
{if $filter || $bps.topbtn || ($blocks && "add"|in_array:$blocks)}
<div style="padding:10px;border:1px solid #ccc">
	<table style="width:100%">
		<tr><td>
		{foreach $filter as $k => $v}
			{if $v.type eq 'text' || $v.type eq 'int'}
			<input type="text" name="{$k}" value="{if $search.$k}{$search.$k}{/if}" placeholder="请输入{$v.name}" style="{if $v.style}{$v.style};{else}width:auto;{/if}" />
			{elseif $v.type eq 'date'}
			<input type="text" class="datepicker" name="{$k}" value="{if $search.$k}{$search.$k}{/if}" style="{if $v.style}{$v.style};{else}width:auto;{/if}" autocomplete="off" />
			{elseif $v.type eq 'time'}
			<input type="text" class="datetimepicker" name="{$k}" value="{if $search.$k}{$search.$k}{/if}" style="{if $v.style}{$v.style};{else}width:auto;{/if}" autocomplete="off" />
			{elseif $v.type eq 'select'}
			<select class="search_selector" name="{$k}" style="{if $v.style}{$v.style};{else}width:auto;{/if}">
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
		{if $blocks && "add"|in_array:$blocks}
		<input type="button" value="添加{$title}" onclick="javascript:openWindow('{$bps.action}{$bps.options.link_add}','添加{$title}');" />
		{/if}
		{if $bps.topbtn}
		{foreach $bps.topbtn as $topbtn}
			{if $topbtn.path}
			<input type="button" value="{$topbtn.name}" onclick="javascript:openWindow('{$topbtn.path}','{$title} > {$topbtn.name}');"/>
			{else}
			<input type="button" value="{$topbtn.name}" onclick="javascript:location.href='{$topbtn.href}';" />
			{/if}
		{/foreach}
		{/if}
		</td>
		</tr>
	</table>
</div>
</form>
<script type="text/javascript">
$(function(){
	$('.search_selector').change(function(){
		$('#search_form').submit();
	});
});
</script>
{/if}