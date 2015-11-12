{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 编辑{if $app.is_app eq 'YES'}应用{else}菜单{/if} “{$app.name}” 信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">ID</td>
		<td class="value">{$app.id}</td>
	</tr>
	<tr>
		<td class="field">创建类型</td>
		<td class="value">{if $app.is_app eq 'YES'}应用{else}菜单{/if}</td>
	</tr>
	{if $app.pid eq '0'}
	<tr>
		<td class="field">顶部位置</td>
		<td class="value"><input style="width:18px" maxlength="2" type="text" name="order" value="{$app.order}" />
		(从左到右,1~99)</td>
	</tr>
	{else}
	<tr>
		<td class="field">显示层次</td>
		<td class="value"><select id="sel_pid" class="common" name="pid"></select></td>
	</tr>
	{/if}
	<tr>
		<td class="field">显示名称 *</td>
		<td class="value"><input class="common" type="text" name="name" value="{$app.name}" /></td>
	</tr>
	<tr>
		<td class="field">角色选择 *</td>
		<td class="value">
			{include file="acl/forms/roles_edit.tpl"}
		</td>
	</tr>
	<tr id="sel_path" {if !$app.is_app || $app.is_app eq 'NO'}class="disn"{/if}>
		<td class="field">应用路径 *</td>
		<td class="value"><input class="common" type="text" name="path" value="{$app.path}" /></td>
	</tr>
	<tr>
		<td class="submit" colspan="2">
			<input type="submit" value="提交" />
			<input type="button" value="返回" onclick="javascript:history.go(-1);" />
		</td>
	</tr>
</table>
</form>

<script type="text/javascript">
// only when element exists
if ($("#sel_pid").length > 0) {
	var opt = '{if $app.is_app eq 'YES'}app{else}menu{/if}';
	$.post('appAjax', { opt : opt , sel : '{$app.pid}' }, function(text){
		$("#sel_pid").append(text);
	});
}
</script>

</div>

{include file="frame/foot.tpl"}