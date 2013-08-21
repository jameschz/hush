<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:49:09
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\auth\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:1505251773a150015d0-06904574%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'fa567bf0bd591932cec0c7cecf0996ad486b63d5' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\auth\\index.tpl',
      1 => 1357552553,
    ),
  ),
  'nocache_hash' => '1505251773a150015d0-06904574',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>用户登录</title>
<link href="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
css/login.css" rel="stylesheet" type="text/css" />

<script type="text/javascript">
if(self!=top){top.location=self.location;}
</script>

</head>
<body>
<div class="login-body">
	<div class="login-con">
	<h1><img src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
img/logo_s.gif" /><span>后台管理系统</span></h1>
		<div class="login">
		<?php $_template = new Smarty_Internal_Template("frame/error.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>

		<form action="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
auth/login" method="post">
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
					<img id="securityimg" src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
app/scode/image.php" alt="看不清？点击更换" align="absmiddle" style="cursor:pointer" onClick="this.src=this.src+'?'" />
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
