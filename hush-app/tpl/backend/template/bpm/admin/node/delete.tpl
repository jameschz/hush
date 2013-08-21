{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 删除流程节点
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<table class="flow_model_field_table">
	<tr><td>
		<input id="bmf_return" type="button" class="btn" value="返回" />
		<input id="bmf_cancel" type="button" class="btn" value="关闭" />
	</td></tr>
</table>

<script type="text/javascript">
$('#bmf_return').click(function(){
	$.form.jumpto('{$smarty.server.HTTP_REFERER}');
});

$('#bmf_cancel').click(function(){
	parent.location.reload();
});
</script>

</div>

{include file="frame/foot.tpl"}