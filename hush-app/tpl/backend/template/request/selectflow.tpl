{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 创建申请
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">申请流程 *</td>
		<td class="value">
			<select class="common" id="bpm_flow_id">
				<option value="">请选择申请流程</option>
				{foreach $flowList as $flow}<option value="{$flow.bpm_flow_id}">{$flow.bpm_flow_name}</option>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/request/sendList');" />
		</td>
	</tr>
</table>
</form>

<script type="text/javascript">
$('#bpm_flow_id').get(0).onchange=function(){
	var flow_id = $("#bpm_flow_id option:selected").val();
	$.form.jumpto('/request/create/flowId/' + flow_id);
};
</script>

</div>

{include file="frame/foot.tpl"}