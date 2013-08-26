{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 测试接口调试
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
			<td class="value"><input class="common" type="text" name="paramVal" value="{$pv.dval}"/> ({$pv.desc})</td>
		</tr>
		{/foreach}
		{else}
		<tr>
			<td class="field">@{$k}</td>
			<td class="value">{$v}</td>
		</tr>
		{/if}
	{/foreach}
	<tr>
		<td class="field">Operation</td>
		<td class="value">
			<input type="button" value="模拟请求" onclick="javascript:apiTest();"/>
			<input type="button" value="返回列表" onclick="javascript:history.go(-1);"/>
		</td>
	</tr>
	<tr>
		<td class="field">Result</td>
		<td class="value"><textarea id="result" style="width:100%;height:200px;"></textarea></td>
	</tr>
</table>

</div>

<script type="text/javascript">
function apiTest () {
	var headers = arguments[0] || {};
	var action = $('#action').val();
	var method = $('#method').val();
	var keys = new Array();
	var vals = new Array();
	var data = '';
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
		'headers' : headers,
		'type' : method,
		'url' : action,
		'data' : data,
		'success' : function(msg){
			$('#result').val(msg);
		}	
	});
}
</script>

{include file="frame/foot.tpl"}