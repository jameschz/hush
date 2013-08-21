{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 新增流程基本信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">流程名称 *</td>
		<td class="value"><input class="common" type="text" name="bpm_flow_name" value="{$flow.bpm_flow_name}" /></td>
	</tr>
	<tr>
		<td class="field">流程说明</td>
		<td class="value"><textarea class="common" name="bpm_flow_desc">{$flow.bpm_flow_desc}</textarea></td>
	</tr>
	<tr>
		<td class="field">模型绑定 *</td>
		<td class="value"><input type="button" value="详细信息" onclick="javascript:$.form.jumpto('/bpm/adminSaveModel/flowId/{$flow.bpm_flow_id}');" /></td>
	</tr>
	<tr>
		<td class="field">节点配置 *</td>
		<td class="value"><input type="button" value="详细信息" onclick="javascript:$.form.jumpto('/bpm/adminSaveChart/flowId/{$flow.bpm_flow_id}');" /></td>
	</tr>
	<tr>
		<td class="field">流程状态 *</td>
		<td class="value">
			<input type="radio" name="bpm_flow_status" value="0" {if $flow.bpm_flow_status == 0}checked{/if} />&nbsp;未确认&nbsp;
			<input type="radio" name="bpm_flow_status" value="1" {if $flow.bpm_flow_status == 1}checked{/if} />&nbsp;已确认&nbsp;
		</td>
	</tr>
	<tr>
		<td class="field">角色选择 *</td>
		<td class="value">
			{include file="acl/forms/roles_edit.tpl"}
		</td>
	</tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="submit" value="保存" />
			<input type="button" value="返回" onclick="javascript:$.form.jumpto('/bpm/');" />
		</td>
	</tr>
</table>
</form>

</div>

{include file="frame/foot.tpl"}