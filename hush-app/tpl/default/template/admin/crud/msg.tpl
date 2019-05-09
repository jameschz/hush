{include file="frame/head.tpl"}

<div class="mainbox">

{include file="frame/error.tpl"}

<table class="titem">
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="关闭并返回列表" onclick="javascript:parent.close();" />
		</td>
	</tr>
</table>

</div>

{if !$_no_foot}
{include file="frame/foot.tpl"}
{/if}