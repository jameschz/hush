{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 修改流程节点
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="flow_model_field_table">
	<tr id="bpm_node_attr"><td>
		{foreach $nodeAttrOptions as $value => $text}<input name="bpm_node_attr" type="radio" value="{$value}" {if $node.bpm_node_attr == $value}checked{/if}/> {$text} 
		{/foreach}
	</td></tr>
	<tr id="bpm_node_name"><td>
		<input name="bpm_node_name" class="text focus_select" value="{if $node.bpm_node_name}{$node.bpm_node_name}{else}填写节点名{/if}" />
	</td></tr>
	<tr id="bpm_node_type"><td>
		<select id="bmf_select" name="bpm_node_type">
			<option value="" {if !$node.bpm_node_type}selected{/if}>选择类型>>></option>
			{html_options options=$nodeTypeOptions selected=$node.bpm_node_type}
		</select>
	</td></tr>
	<tr class="flow_node_helper"><td>
		常用工具 : 
		<a href="javascript:;" onclick="javascript:$('.flow_node_helper_tab').hide();$('.helper_pbel').toggle();">PBEL语法</a> | 
		<a href="javascript:;" onclick="javascript:$('.flow_node_helper_tab').hide();$('.helper_quick').toggle();">快捷提示</a> | 
		<a href="javascript:;" onclick="javascript:clearPath();">清除路径</a>
	</td></tr>
	{if $pbelDocs}
	<tr class="flow_node_helper_tab helper_pbel disn"><td>
		<a href="javascript:addText('return true; // exit');">逻辑结束 : return true;</a><br/>
		{foreach $pbelDocs as $doc}<a href="javascript:addText('pbel.{$doc.method}');">{$doc.intros}</a><br/>
		{/foreach}
	</td></tr>
	{/if}
	{if $nodeList}
	<tr class="flow_node_helper_tab helper_quick"><td>
		节点选择 : 
		{foreach $nodeList as $nodeItem}<a href="javascript:addText('pbel.forward({$nodeItem.bpm_node_id})');">{$nodeItem.bpm_node_name}</a>
			{if $nodeItem@index != $nodeItem@total - 1} , {/if}
		{/foreach}
	</td></tr>
	{/if}
	{if $roleList}
	<tr class="flow_node_helper_tab helper_quick"><td>
		角色选择 : 
		{foreach $roleList as $role}<a href="javascript:addText('pbel.audit_by_role({$role.id})');">{$role.name}</a>
			{if $role@index != $role@total - 1} , {/if}
		{/foreach}
	</td></tr>
	{/if}
	{foreach $modelList as $modelId => $fieldList}
	<tr class="flow_node_helper_tab helper_quick"><td>
		模型选择 : 
		<a href="javascript:addText('pbel.model_form_add({$modelId})');">模型{$modelId}</a> &lt; 
		{foreach $fieldList as $field}<a href="javascript:addText('pbel.model_field({$field.bpm_model_field_id})');">{$field.bpm_model_field_name}</a>
			{if $field@index != $field@total - 1} , {/if}
		{/foreach}
		&gt;
	</td></tr>
	{/foreach}
	<tr id="bpm_node_code"><td>
		<textarea id="bpm_node_code_text" name="bpm_node_code" class="select focus_select">{if $node.bpm_node_code}{$node.bpm_node_code}{else}### PBEL语言可以使用PHP语法 ###{/if}</textarea>
	</td></tr>

	<tr><td>
		<input id="bmf_submit" type="submit" class="btn" value="保存" />
		<input id="bmf_delete" type="button" class="btn" value="删除" />
		<input id="bmf_cancel" type="button" class="btn" value="取消" />
	</td></tr>
</table>
</form>

<script type="text/javascript">
function addText (str) {
	$.form.addtext('bpm_node_code_text', str);
}

function clearPath () {
	if (confirm('确认删除该节点路径?')) {
		$.get('/bpm/adminPathClear/nodeId/{$node.bpm_node_id}', function(){
			parent.location.reload();
		});
	}
}

$(document).ready(function(){
	$('#bmf_delete').click(function(){
		$.form.confirm('/bpm/adminNodeDelete/nodeId/{$node.bpm_node_id}', '确认删除该节点?');
	});
	$('#bmf_cancel').click(function(){
		parent.location.reload();
	});
});
</script>

</div>

{include file="frame/foot.tpl"}