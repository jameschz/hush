{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 添加数据模型字段
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<input type="hidden" name="bpm_model_id" value="{$modelId}" />
<table class="flow_model_field_table">
	<tr id="bpm_model_field_name"><td>
		<input name="bpm_model_field_name" class="text focus_select" value="{if $field.bpm_model_field_name}{$field.bpm_model_field_name}{else}填写字段名{/if}" />
	</td></tr>
	<tr id="bpm_model_field_type"><td>
		<select id="bmf_select" name="bpm_model_field_type">
			<option value="" {if !$field.bpm_model_field_type}selected{/if}>选择类型>>></option>
			{html_options options=$fieldTypeOptions selected=$field.bpm_model_field_type}
		</select>
	</td></tr>
	<tr id="bpm_model_field_attr"><td>
		<textarea name="bpm_model_field_attr" class="short" readonly>{if $field.bpm_model_field_attr}{$field.bpm_model_field_attr}{/if}</textarea>
	</td></tr>
	<tr id="bpm_model_field_length"><td>
		<input name="bpm_model_field_length" class="text focus_select" value="{if $field.bpm_model_field_length}{$field.bpm_model_field_length}{else}填写限制长度{/if}" />
	</td></tr>
	<tr id="bpm_model_field_option"><td>
		<textarea name="bpm_model_field_option" class="select focus_select">{if $field.bpm_model_field_option}{$field.bpm_model_field_option}{else}### 字段选项格式如下 ###
value1=option1
value2=option2{/if}</textarea>
	</td></tr>
	<tr><td>
		<input id="bmf_submit" type="submit" class="btn" value="保存" />
		<input id="bmf_cancel" type="button" class="btn" value="取消" />
	</td></tr>
</table>
</form>

<script type="text/javascript">
function selectType () {
	var selectVal = $("#bmf_select option:selected").val();
	var selectType = $("#bmf_select option:selected").html().toLowerCase();
	if (selectVal == '') {
		$('tr[id=bpm_model_field_length]').hide();
		$('tr[id=bpm_model_field_option]').hide();
	} else if (selectType == 'text') {
		$('tr[id=bpm_model_field_length]').show();
		$('tr[id=bpm_model_field_option]').hide();
		$('input[name=bpm_model_field_length]').attr('disabled', '');
		$('textarea[name=bpm_model_field_option]').attr('disabled', 'true');
	} else {
		$('tr[id=bpm_model_field_length]').hide();
		$('tr[id=bpm_model_field_option]').show();
		$('input[name=bpm_model_field_length]').attr('disabled', 'true');
		$('textarea[name=bpm_model_field_option]').attr('disabled', '');
	}
	if ($("#bmf_select option:selected").val()) {
		$('textarea[name=bpm_model_field_attr]').html('class=' + selectType);
	} else {
		$('textarea[name=bpm_model_field_attr]').html('');
	}
}

selectType(); // default operation
$('#bmf_select').get(0).onchange = selectType;

$(document).ready(function(){
	$.form.select('.focus_select');
	$('#bmf_cancel').click(function(){
		parent.location.reload();
	});
});
</script>

</div>

{include file="frame/foot.tpl"}