<!DOCTYPE html>
<html lang="zh_CN">
<head>
<meta http-equiv="content-type" content="text/html;charset=utf-8" />
<meta http-equiv="content-language" content="zh_CN" />
<meta http-equiv="content-style-type" content="text/css" />
<meta http-equiv="content-script-type" content="text/javascript" />
<meta name="viewport" content="width=device-width,minimum-scale=1.0,maximum-scale=1.0,user-scalable=no,initial-scale=1"/>
<meta name="robots" content="index,follow,noodp,noydir" />
<meta name="Keywords" content="" />
<meta name="Description" content="" />
<meta name="copyright" content="" />
<title>{$_app}</title>
<link rel="canonical" href=""/>
<link href="{$_root}css/hush.css" rel="stylesheet" type="text/css" />
<link href="{$_root}css/font.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$_root}js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="{$_root}js/jquery.easing.min.js"></script>
<script type="text/javascript" src="{$_root}js/hush.js"></script>
<script type="text/javascript" src="{$_root}js/placeholder.js"></script>
<script type="text/javascript">
if (self != top) top.location = self.location;
$(document).keypress(function(event){
	var keycode = (event.keyCode ? event.keyCode : event.which);
	if (keycode == '13') $('#login_form').submit();
});
</script>
</head>
<body>
    <!-- set login page background here -->
	<div class="ax_loding" style="background:#fff"></div>
	<form action="{$_root}auth/login" method="post" id="login_form">
	<div class="ax_loding_nr">
        <!-- set login page logo icon here -->
		<div class="ax_loding_logo"><img src="{$_root}img/logo.png"></div>
		{if $errors}
			<div class="ax_error_box">
			{foreach $errors as $error}
				<span><b>!</b> {$error}</span>
			{/foreach}
			</div>
		{/if}
		<div class="ax_srnr">
			<div class="ax_srnr_mr"><i class="icon icon-user-tie"></i><input class="text" type="text" placeholder="请输入您的用户名" name="username" value="{$smarty.post.username}" /></div>
			<div class="ax_srnr_mr"><i class="icon icon-key2"></i><input class="text" type="password" placeholder="请输入您的密码" name="password" /></div>
			<div class="ax_srnr_mr"><i class="icon icon-shield"></i><input class="text" type="text" placeholder="请输入验证码" name="securitycode" autocomplete="off" />
			<img id="securityimg" src="{$_root}app/scode/image.php?env={$_env}" alt="看不清？点击更换" align="absmiddle" style="cursor:pointer" onClick="javascript:this.src=this.src+'#';" /></div>
		</div>
		<div class="ax_srnr_botton" onclick="$('#login_form').submit();">管理员登录</div>
	</div>
	</form>
	<p class="ax_bottom">上海凡嘉信息技术有限公司版权所有</p>
</body>
</html>