{include file="frame/head.tpl"}

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem">
{foreach $field as $k => $v}
	<tr>
		<td class="field">{$v.name}</td>
		<td class="value">
		{if $v.files}
		{include file="base/crud/item_file.tpl" from=info}
		{else}
		{include file="base/crud/item.tpl" from=info}
		{/if}
		</td>
	</tr>
{/foreach}
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="确认并返回列表" onclick="javascript:parent.close();" />
			{if $smarty.get._back}
			<input type="button" value="返回" onclick="javascript:location.href='{$smarty.get._back}';" />
			{/if}
		</td>
	</tr>	
</table>
</form>

</div>

{if !$_no_foot}
{include file="frame/foot.tpl"}
{/if}