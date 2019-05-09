{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 编辑 ACL 资源 “{$resource.name}” 信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">ID</td>
		<td class="value">{$resource.id}</td>
	</tr>
	<tr>
		<td class="field">资源名 *</td>
		<td class="value"><input class="common" type="text" name="name" value="{$resource.name}" /></td>
	</tr>
	<tr>
		<td class="field">资源说明 *</td>
		<td class="value"><input class="common" type="text" name="description" value="{$resource.description}" /></td>
	</tr>
	<tr>
		<td class="field">应用范围 *</td>
		<td class="value">
			<select name="app_id" class="common tree">
				<option value="0">默认全局范围（作用于整个系统）</option>
				{foreach $appopts as $menus}
				<optgroup label="{$menus.name}" title="">
					{foreach $menus.list as $apps}
					<optgroup label="└{$apps.name}" title="">
						{html_options options=$apps.list selected=$resource.app_id}
					</optgroup>
					{/foreach}
				</optgroup>
				{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td class="field">角色权限 *</td>
		<td class="value">
			{include file="acl/forms/roles_edit.tpl"}
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

</div>

{include file="frame/foot.tpl"}