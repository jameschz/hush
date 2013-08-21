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
		<td class="submit" colspan="2">
			<input type="submit" value="保存" />
			<input type="button" value="返回" onclick="javascript:history.go(-1);" />
		</td>
	</tr>
</table>
</form>

</div>

{include file="frame/foot.tpl"}