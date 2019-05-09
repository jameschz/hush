{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 个人信息修改
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form method="post">
<table class="titem" >
	<tr>
		<td class="field">用户名 *</td>
		<td class="value">{$user.name}</td>
	</tr>
	<tr>
		<td class="field">用户密码</td>
		<td class="value"><input class="common" type="password" name="pass" value="" /></td>
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