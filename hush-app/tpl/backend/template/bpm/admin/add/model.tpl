{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 为流程 “{$flow.bpm_flow_name}” 定义数据模型
</div>

<div class="mainbox">

<form id="save_model_form" method="post">
<input type="hidden" name="bpm_model_id" value="{$model.bpm_model_id}" />
<input type="hidden" name="bpm_model_form" />
<div style="margin-bottom:10px;">
	<input id="add_model_field" type="button" value="添加字段" />&nbsp;
	<input id="save_model_as_form" type="button" value="保存模型" />
</div>
</form>

<div class="flow_model_add_box">
	<div class="flow_model_add_body">
		<table class="flow_model_field_table">
		{foreach $fieldList as $field}
		<tr>
			<td class="field">{$field.name}</td>
			<td class="value">{$field.form}</td>
			<td class="value"><input type="button" id="{$field.id}" class="edit_model_field" value="修改" /></td>
		</tr>
		{/foreach}
		</table>
	</div>
</div>

<table id="flow_model_form" class="disn">
{foreach $fieldList as $field}<tr><td class="field">{$field.name}</td><td class="value">{$field.form}</td></tr>
{/foreach}
</table>

<script type="text/javascript">
$('#add_model_field').click(function(){
	$.overlay.frame('/bpm/adminFieldAdd/modelId/{$model.bpm_model_id}', function(){});
});
$('.edit_model_field').click(function(){
	$.overlay.frame('/bpm/adminFieldEdit/fieldId/' + $(this).attr('id'), function(){});
});
$('#save_model_as_form').click(function(){
	{if $fieldList}
		var formData = $('#flow_model_form').html().replace(/<\/?tbody>/g, '');
		$('input[name=bpm_model_form]').val(formData);
		$('#save_model_form').submit();
	{else}
		alert('请先添加字段');
	{/if}
});
</script>

</div>

{include file="frame/foot.tpl"}