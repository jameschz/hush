{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 我收到的申请
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left">&nbsp;ID</th>
			<th align="left">所属流程</th>
			<th align="left">请求主题</th>
			<th align="left">是否处理</th>
			<th align="left">审核结果</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $requestList as $request}
		<tr>
			<td align="left">{$request.bpm_request_id}</td>
			<td align="left">{$request.bpm_flow_name}</td>
			<td align="left"><a href='/request/audit/reqId/{$request.bpm_request_id}/auditId/{$request.bpm_request_audit_id}'><u>{$request.bpm_request_subject}</u></a></td>
			<td align="left">
			{if $request.bpm_request_audit_done == 0}<font color="red">未处理</font>
			{elseif $request.bpm_request_audit_done == 1}<font color="green">已完成</font>
			{else}<font color="gray">未知状态</font>
			{/if}
			</td>
			<td align="left">
			{if $request.bpm_request_audit_status == 0}<font color="red">未通过</font>
			{elseif $request.bpm_request_audit_status == 1}<font color="green">已通过</font>
			{else}<font color="gray">未知状态</font>
			{/if}
			</td>
			<td align="right">
				<a href="/request/audit/reqId/{$request.bpm_request_id}/auditId/{$request.bpm_request_audit_id}">审核申请</a>
			</td>
		</tr>
		{/foreach}
	</tbody>
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}