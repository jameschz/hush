{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 审核申请
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form id="audit_form" method="post">
<input type="hidden" name="bpm_request_audit_status" value="" />
	<table class="titem">
	<tr><td class="field">申请人</td><td class="value">{$request.author_name}</td></tr>
	<tr><td class="field">申请主题</td><td class="value">{$request.bpm_request_subject}</td></tr>
	{foreach $request.bpm_request_body_hash as $k => $v}<tr><td class="field">{$k}</td><td class="value">{$v}</td></tr>
	{/foreach}
	<tr><td class="field">附加说明</td><td class="value">
		<textarea name="bpm_request_comment" class="select focus_select"></textarea>
	</td></tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="通过" id="pass_btn" />
			<input type="button" value="拒绝" id="fail_btn" />
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/request/recvList');" />
		</td>
	</tr>
	</table>
</form>

<script type="text/javascript">
$('#pass_btn').click(function(){
	if (confirm('确认通过该申请，是否继续？')) {
		$('input[name=bpm_request_audit_status]').val(1);
		$('#audit_form').submit();
	}
});
$('#fail_btn').click(function(){
	if (confirm('确认拒绝该申请，是否继续？')) {
		$('input[name=bpm_request_audit_status]').val(0);
		$('#audit_form').submit();
	}
});
</script>

</div>

{include file="frame/foot.tpl"}