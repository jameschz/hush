{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 取消申请
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form id="cancel_form" method="post">
<input type="hidden" name="action" value="cancel" />
	<table class="titem">
	<tr><td class="field">申请主题</td><td class="value">{$request.bpm_request_subject}</td></tr>
	{foreach $request.bpm_request_body_hash as $k => $v}<tr><td class="field">{$k}</td><td class="value">{$v}</td></tr>
	{/foreach}
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="提交" id="submit_btn" />
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/request/sendList');" />
		</td>
	</tr>
	</table>
</form>

<script type="text/javascript">
$('#submit_btn').click(function(){
	if (confirm('本次取消申请操作不可恢复，是否继续？')) {
		$('#cancel_form').submit();
	}
});
</script>

</div>

{include file="frame/foot.tpl"}