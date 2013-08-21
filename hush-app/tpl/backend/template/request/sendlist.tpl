{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 我发送的申请
</div>

<div class="mainbox">

<table class="tlist" >
	<thead>
		<tr class="title">
			<th align="left">&nbsp;ID</th>
			<th align="left">所属流程</th>
			<th align="left">请求主题</th>
			<th align="left">所处流程</th>
			<th align="left">申请状态</th>
			<th align="right">操作&nbsp;</th>
		</tr>  
	</thead>
	<tbody>
		{foreach $requestList as $request}
		<tr>
			<td align="left">{$request.bpm_request_id}</td>
			<td align="left">{$request.bpm_flow_name}</td>
			<td align="left"><a href='/request/view/reqId/{$request.bpm_request_id}'><u>{$request.bpm_request_subject}</u></a></td>
			<td align="left">{$request.bpm_node_name}</td>
			<td align="left">
			{if $request.bpm_request_status == 1}<font color="red">未完成</font>
			{elseif $request.bpm_request_status == 2}<font color="red">未完成</font>
			{elseif $request.bpm_request_status == 3}<font color="green">已完成</font>
			{else}<font color="gray">未知状态</font>
			{/if}
			</td>
			<td align="right">
			{if $request.bpm_request_status != 3}<a href="/request/cancel/reqId/{$request.bpm_request_id}">取消申请</a>
			{else}取消申请
			{/if}
			</td>
		</tr>
		{/foreach}
	</tbody>
	<tfoot>
		<tr>
			<td colspan="6">
				<button type="button" class="btn1s" onclick="javascript:location.href='selectFlow';">创建申请</button>
			</td>
		</tr>
	</tfoot>
</table>

{include file="frame/page.tpl"}

</div>

{include file="frame/foot.tpl"}