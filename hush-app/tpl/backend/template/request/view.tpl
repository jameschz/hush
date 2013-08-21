{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 浏览申请
</div>

<div class="mainbox">

<form method="post">
	<table class="titem">
	<tr><td class="field">申请主题</td><td class="value">{$request.bpm_request_subject}</td></tr>
	{foreach $request.bpm_request_body_hash as $k => $v}<tr><td class="field">{$k}</td><td class="value">{$v}</td></tr>
	{/foreach}
	<tr><td class="field">所处流程</td><td class="value">{$request.bpm_node_name}</td></tr>
	<tr><td class="field">附加说明</td><td class="value">{$request.bpm_request_comment}</td></tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="刷新" onclick="javascript:location.reload();" />
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/request/sendList');" />
		</td>
	</tr>
	</table>
</form>

{if $requestOp}
<hr class="common"/>
<table>
	<tbody>
		{foreach $requestOp as $op}
		<tr>
			<td align="center">{date('Y-m-d H:i:s', $op.bpm_request_op_time)}</td>
			<td align="center">&nbsp;|&nbsp;</td>
			<td align="center">{$op.user_name}</td>
			<td align="center">&nbsp;|&nbsp;</td>
			<td align="center">{$op.bpm_request_op_action}</td>
		</tr>
		{/foreach}
	</tbody>
</table>
<hr class="common"/>
{/if}

<script type="text/javascript">

</script>

</div>

{include file="frame/foot.tpl"}