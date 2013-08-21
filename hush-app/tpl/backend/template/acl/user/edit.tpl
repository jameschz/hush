{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 编辑用户 “{$user.name}” 信息
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">ID</td>
		<td class="value">{$user.id}</td>
	</tr>
	<tr>
		<td class="field">用户名 *</td>
		<td class="value"><input class="common" type="text" name="name" value="{$user.name}" {if $user.name eq $_sa}readonly{/if} /></td>
	</tr>
	<tr>
		<td class="field">角色选择 *</td>
		<td class="value">
			{include file="acl/forms/roles_edit.tpl"}
		</td>
	</tr>
	{if $_acl->isAllowed($_admin.role, 'acl_user_passwd')}
	<tr>
		<td class="field">新密码</td>
		<td class="value"><input class="common" type="text" name="pass" value="" /></td>
	</tr>
	{/if}
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