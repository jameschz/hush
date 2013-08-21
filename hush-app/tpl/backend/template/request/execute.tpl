{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 创建申请
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
	<table class="titem">
	<tr><td class="field">申请主题</td><td class="value"><input name="bpm_request_subject" class="text" type="text" value="{$request.bpm_request_subject}" /></td></tr>
	{$modelform}
	<tr>
		<td class="submit" colspan="2">
			<input type="submit" value="保存" />
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/request/addStep1');" />
		</td>
	</tr>
	</table>
</form>

<script type="text/javascript">

</script>

</div>

{include file="frame/foot.tpl"}