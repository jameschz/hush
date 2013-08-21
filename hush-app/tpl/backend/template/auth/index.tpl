<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户登录</title>
<link href="{$_root}css/main.css" rel="stylesheet" type="text/css" />
<link href="{$_root}css/login.css" rel="stylesheet" type="text/css" />
{literal}
<script type="text/javascript">
if(self!=top){top.location=self.location;}
</script>
{/literal}
</head>
<body>
<div class="login-body">
	<div class="login-con">
	<h1><img src="{$_root}img/logo_s.gif" /><span>后台管理系统</span></h1>
		<div class="login">
		{include file="frame/error.tpl"}
		<form action="{$_root}auth/login" method="post">
			<input type="hidden" name="go" value=""/>
			<input type="hidden" name="do" value="login"/>
			<ul>
				<li>
					<label>用户名：</label>
					<input type="text" class="text" name="username" style="_width:208px;"/>
				</li>
				<li>
					<label>密　码：</label>
					<input type="password" class="text" name="password" style="_width:208px;"/>
				</li>
				<li>
					<label>验证码：</label>
					<input type="text" class="text" style="width: 50px;margin-right:5px; text-transform: uppercase;" id="securitycode" name="securitycode" autocomplete="off"/>
					<img id="securityimg" src="{$_root}app/scode/image.php" alt="看不清？点击更换" align="absmiddle" style="cursor:pointer" onClick="this.src=this.src+'?'" />
				</li>
				<li>
					<input type="submit" onclick="this.form.submit();" class="submit" value="登录" name="sm1"/>
				</li>
			</ul>
		</form>  
		</div>
	</div>
</div>
</body>
</html>
