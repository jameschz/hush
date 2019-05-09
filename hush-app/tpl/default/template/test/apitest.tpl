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
	{foreach $config as $k => $v}
		{if is_array($v)}
		{foreach $v as $pk => $pv}
		<tr>
			<td class="field" style="width:200px">@params {$pk}<input type="hidden" name="paramKey" value="{$pk}"/></td>
			<td class="value"><input class="common" type="text" name="paramVal" value="{$pv.dval|replace:"\"":"&#34;"}"/> ({$pv.desc|escape})</td>
		</tr>
		{/foreach}
		{else}
		<tr>
			<td class="field">@{$k}</td>
			{if $k eq 'header'}
			<td class="value" id="headerJsonString" style="background:#ffffe0">
				<input id="headerJsonInput" style="width:100%" type="text" value='{literal}{"X-OS":0,"X-SDK":"1","X-APPID":"10000","X-DEVICE":"DEBUG","X-CHANNEL":"A1018"}{/literal}' />
			</td>
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
	<tr>
		<td class="field">Operation</td>
		<td class="value">
			<input type="button" value="签名加密" onclick="javascript:apiSign();"/>
			<input type="button" value="模拟请求" onclick="javascript:apiTest();"/>
			<input type="button" value="返回列表" onclick="javascript:location.href='/test/';"/>
		</td>
	</tr>
	<tr>
		<td class="field">Result</td>
		<td class="value"><textarea id="result" style="width:100%;height:200px;"></textarea></td>
	</tr>
</table>

<div id="openWindowBox" class="windowbox disn">
	<div class="windownav">
		<img src="{$_host_s}/img/icon_wrong.png" class="close" onclick="javascript:$.overlay.close(0,true)"/>
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
function apiSign () {
	var headers = arguments[0] || $('#headerJsonInput').val();
	var keys = new Array();
	var vals = new Array();
	var data = 'header=' + encodeURIComponent(headers) + '&';
	{if $config.encode}
	// pass encode params
	data += 'encode={$config.encode}&';
	{/if}
	{if $config.token}
	// pass encode params
	data += 'token=' + $("#token").val() + '&';
	{/if}
	$('input[name=paramKey]').each(function(){
		var key = $.trim($(this).val());
		keys.push(encodeURIComponent(key));
	});
	$('input[name=paramVal]').each(function(){
		var val = $.trim($(this).val());
		vals.push(encodeURIComponent(val));
	});
	for(var i=0; i<keys.length; i++){
		data += keys[i] + '=' + vals[i] + '&';
	}
	$.ajax({
		'type' : 'POST',
		'dataType' : 'JSON',
		'url' : '/test/apiSign',
		'data' : data,
		'success' : function(data){
			$('#headerJsonInput').val(JSON.stringify(data.header));
			$('#paramsEncodeStringVal').val(data.params);
		}	
	});
}
function apiTest () {
	var headers = arguments[0] || $('#headerJsonInput').val();
	var action = $('#action').val();
	var method = $('#method').val();
	var keys = new Array();
	var vals = new Array();
	// get encrypted data
	var data = $('#paramsEncodeStringVal').val();
	{if !$config.encode}
	// get inputed data
	if (!data) {
		data = '';
		$('input[name=paramKey]').each(function(){
			var key = $.trim($(this).val());
			keys.push(encodeURIComponent(key));
		});
		$('input[name=paramVal]').each(function(){
			var val = $.trim($(this).val());
			vals.push(encodeURIComponent(val));
		});
		for(var i=0; i<keys.length; i++){
			data += keys[i] + '=' + vals[i] + '&';
		}
	}
	{/if}
	$.ajax({
		'headers' : eval('(' + headers + ')'),
		'type' : method,
		'url' : action,
		'data' : data,
		'success' : function(str){
			$('#result').val(str);
		}	
	});
}
</script>

{include file="frame/foot.tpl"}