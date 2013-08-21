{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 新增流程节点
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="flow_model_field_table">
	<tr><td>
		{foreach $nodeAttrOptions as $value => $text}<input name="bpm_node_attr" type="radio" value="{$value}" {if $node.bpm_node_attr == $value}checked{/if}/> {$text} 
		{/foreach}
	</td></tr>
	<tr><td><input name="bpm_node_name" class="text focus_select" value="{if $node.bpm_node_name}{$node.bpm_node_name}{else}填写节点名{/if}" /></td></tr>
	<tr><td>
		<select id="bmf_select" name="bpm_node_type">
			<option value="" {if !$node.bpm_node_type}selected{/if}>选择类型>>></option>
			{html_options options=$nodeTypeOptions selected=$node.bpm_node_type}
		</select>
	</td></tr>
	<tr><td>
		<input id="bmf_submit" type="submit" class="btn" value="保存" />
		<input id="bmf_cancel" type="button" class="btn" value="取消" />
	</td></tr>
</table>
</form>

<script type="text/javascript">
$(document).ready(function(){
	$.form.select('.focus_select');
	$('#bmf_cancel').click(function(){
		parent.location.reload();
	});
});
</script>

</div>

{include file="frame/foot.tpl"}