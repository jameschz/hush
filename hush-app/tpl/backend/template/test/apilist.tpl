{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 测试接口调试
</div>

<div class="mainbox">

<table class="tlist">
{foreach $apiConfigList as $serviceName => $actionList}
	<tr>
		<td align="left" colspan="4" class="title">{$serviceName}</td>
	</tr>
	{foreach $actionList as $actionName => $actionConfig}
	<tr>
		<td align="left">{$serviceName} -> {$actionName}</td>
		<td align="left">{$actionConfig['title']}</td>
		<td align="left">{$actionConfig['action']}</td>
		<td align="right">
			<a href="apiTest?serviceName={$serviceName}&actionName={$actionName}">测试</a>
		</td>
	</tr>
	{/foreach}
{/foreach}
</table>

</div>

{include file="frame/foot.tpl"}