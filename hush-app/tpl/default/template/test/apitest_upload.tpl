{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_host_s}/img/icon_arrow_right.png" class="icon" /> 测试接口调试
<div style="float:right"><input type="button" value="调试工具" onclick="javascript:openWindow('/test/apiTool');"/></div>
</div>

<div class="mainbox">

<input type="hidden" id="action" value="{$config.action}"/>
<input type="hidden" id="method" value="{$config.method}"/>

<table class="titem" style="width:100%">
	<tr>
		<tr><td class="field" colspan=2>{$smarty.get.serviceName} > {$smarty.get.actionName}</td></tr>
	</tr>
	<form id="uploadForm" method="post" enctype="multipart/form-data" target="resultFrame">
	{foreach $config as $k => $v}
		{if is_array($v)}
		{foreach $v as $pk => $pv}
		<tr>
			<td class="field" style="width:200px">@params {$pk}</td>
			<td class="value"><input class="common" type="file" name="{$pk}" value=""/></td>
		</tr>
		{/foreach}
		{else}
		<tr>
			<td class="field">@{$k}</td>
			{if $k eq 'header'}
			<td class="value" id="headerJsonString" style="background:#ffffe0">{$v}</td>
			{elseif $k eq 'encode'}
			<td class="value" id="paramsEncodeString" style="background:#ffffe0">
			<div style="padding-bottom:3px;">{$v} > </div>
			<textarea id="paramsEncodeStringVal" name="paramsEncodeStringVal" class="common"></textarea>
			</td>
			{elseif $k eq 'token'}
			<td class="value"><input class="common" type="text" name="token" id="token" value="debug123456"/></td>
			{else}
			<td class="value">{$v}</td>
			{/if}
		</tr>
		{/if}
	{/foreach}
	</form>
	<tr>
		<td class="field">Operation</td>
		<td class="value">
			<input type="button" value="模拟请求" onclick="javascript:apiTest();"/>
			<input type="button" value="返回列表" onclick="javascript:location.href='/test/';"/>
		</td>
	</tr>
	<tr>
		<td class="field">Result</td>
		<td class="value">
		<iframe id="resultFrame" name="resultFrame" style="width:100%;"></iframe>
		</td>
	</tr>
</table>


<div id="openWindowBox" class="windowbox disn">
	<div class="windownav">
		<img src="{$_host_s}/img/icon_wrong.png" class="close" onclick="javascript:$.overlay.close(0)"/>
		<img src="{$_host_s}/img/icon_arrow_right.png" class="icon" /> <b></b>
	</div>
	<iframe id="openWindowFrame" frameborder="no" border="0" width="100%" height="500"></iframe>
</div>

<script type="text/javascript">
function openWindow(url){
	$('#openWindowFrame').attr('src', url);
	$.overlay.show($('#openWindowBox'));
}
</script>

</div>

<script type="text/javascript">
function apiTest () {
	var action = $('#action').val();
	if (action) {
		$('#uploadForm').attr('action', action);
		$('#uploadForm').submit();
	}
}
</script>

{include file="frame/foot.tpl"}