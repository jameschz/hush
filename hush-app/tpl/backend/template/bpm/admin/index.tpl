{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 已定义流程列表
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left" style="width:100px;">&nbsp;ID</th>
			<th align="left">流程名</th>
			<th align="left">流程类别</th>
			<th align="left">流程状态</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $bpmFlowList as $flow}
		<tr>
			<td align="left">{$flow.bpm_flow_id}</td>
			<td align="left"><a href='adminEditBasic/flowId/{$flow.bpm_flow_id}'><u>{$flow.bpm_flow_name}</u></a></td>
			<td align="left">{$flow.bpm_flow_type}</td>
			<td align="left">{if $flow.bpm_flow_status == 1}<font color="green">已确认</font>{else}<font color="red">未确认</font>{/if}</td>
			<td align="right">
				<a href="adminEditBasic/flowId/{$flow.bpm_flow_id}">编辑</a> | 删除
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="5">
				<button type="button" class="btn1s" onclick="javascript:location.href='adminAddBasic';">新增</button>
			</td>
		</tr>
	</tfoot>
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}