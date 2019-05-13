{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 审核{$title}信息
</div>

<div class="mainbox">
{include file="frame/error.tpl"}
{include file="base/crud/verify_msg.tpl"}
<form method="post">
<input type="hidden" name="{$bps.pkey}" value="{$item[$bps.pkey]}" />
<table class="titem" >
	<tr>
		<td class="field">审核结果</td>
		<td class="value">
		<select name="status" class="common">
		{foreach $bps.field.status.data as $k => $v}
			<option value="{$k}" {if $item.status eq $k}selected{/if}>{$v}</option>
		{/foreach}
		</select>
		</td>
	</tr>
	<tr>
		<td class="field">审核原因</td>
		<td class="value">
		<textarea class="common" name="result"></textarea>
		</td>
	</tr>
	<tr>
		<td class="submit" colspan="2">
			{include file="base/form/submit.tpl" _submit_once="1"}
		</td>
	</tr>
</table>
</form>
</div>

{include file="frame/datetimepicker.tpl"}
{if !$_no_foot}
{include file="frame/foot.tpl"}
{/if}