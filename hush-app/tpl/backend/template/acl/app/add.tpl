{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 新增 菜单/应用 信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">创建类型</td>
		<td class="value">
			<input type="radio" name="is_app" value="NO" {if !$app.is_app || $app.is_app eq 'NO'}checked{/if} /> 创建菜单 &nbsp;
			<input type="radio" name="is_app" value="YES" {if $app.is_app eq 'YES'}checked{/if} /> 创建应用
		</td>
	</tr>
	<tr>
		<td class="field">显示层次 *</td>
		<td class="value">
			<select id="sel_pid" class="common" name="pid"></select>
		</td>
	</tr>
	<tr>
		<td class="field">显示名称 *</td>
		<td class="value"><input class="common" type="text" name="name" value="{$app.name}" /></td>
	</tr>
	<tr>
		<td class="field">角色选择 *</td>
		<td class="value">
			{include file="acl/forms/roles_add.tpl"}
		</td>
	</tr>
	<tr id="sel_path" class="disn">
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
	var default_opt = '{if $app.is_app eq 'YES'}app{else}menu{/if}';
	// default create menu
	$.post('appAjax', { opt : default_opt , sel : '{$app.pid}' }, function(text){
		$("#sel_pid").append(text);
	});
	// level option list
	$('form input[name=is_app]').click(function(){
		$("#sel_pid").empty();
		var opt = $(this).val() == 'YES' ? 'app' : 'menu';
		$.post('appAjax', { opt : opt , sel : '{$app.pid}' }, function(text){
			$("#sel_pid").append(text);
		})
		if ($(this).val() == 'YES') {
			$("#sel_path").show();
		} else {
			$("#sel_path").hide();
		}
	});
}
</script>

</div>

{include file="frame/foot.tpl"}