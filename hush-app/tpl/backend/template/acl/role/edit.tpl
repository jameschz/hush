{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 编辑角色 “{$role.name}” 信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">ID</td>
		<td class="value">{$role.id}</td>
	</tr>
	<tr>
		<td class="field">角色名 *</td>
		<td class="value"><input class="common" type="text" name="name" value="{$role.name}" /></td>
	</tr>
	<tr>
		<td class="field">角色别名 *</td>
		<td class="value"><input class="common" type="text" name="alias" value="{$role.alias}" /></td>
	</tr>
	<tr>
		<td class="field">可控权限</td>
		<td class="value">
			<table>
				<tr>
					<td>
						<select multiple id="all_role" size="{$max_role_size}" style="width:139px;">
						{foreach $allroles as $option}
							<option value="{$option.id}">{$option.name} ({$option.alias})</option>
						{/foreach}
						</select>
					</td>
					<td>
						<input id="role_add" type="button" value=" &gt; " style="width:20px;" /><br/>
						<input id="role_del" type="button" value=" &lt; " style="width:20px;" />
					</td>
					<td>
						<select multiple id="sel_role" name="privs[]" size="{$max_role_size}" style="width:139px;">
						{foreach $selroles as $option}
							<option value="{$option.id}">{$option.name} ({$option.alias})</option>
						{/foreach}
						</select>
					</td>
				</tr>
			</table>
		</td>
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
// set add & remove options
$('#role_add').click(function(){
	$('#all_role option:selected').each(function(){
		if ($("#sel_role option:contains('"+$(this).text()+"')").length < 1) {
			$('#sel_role').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		}
	});
	$('#all_role option:selected').each(function(){
		$(this).attr('selected', false);
	});
});
// remove select categories
$('#role_del').click(function(){
	$('#sel_role option:selected').each(function(){
		$(this).remove();
	});
});
// select all roles
$('form').submit(function(){
	$('#sel_role option').attr('selected', true);
	return true;
});
</script>

</div>

{include file="frame/foot.tpl"}