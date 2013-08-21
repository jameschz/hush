<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:49:15
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\index\index.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2214651773a1ba929e0-70180453%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '3973e42cae9322bbbc1ba2adb662589f5db10ae8' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\index\\index.tpl',
      1 => 1357872688,
    ),
  ),
  'nocache_hash' => '2214651773a1ba929e0-70180453',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iHush Tracking Console System</title>
<link href="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
css/frame.css" rel="stylesheet" type="text/css" />
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/frame.js" language="javascript" type="text/javascript"></script>
</head>

<body class="showmenu" scroll="no">

<div class="pagemask"></div>

<iframe class="iframemask"></iframe>

<?php $_template = new Smarty_Internal_Template("index/frame/head.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<?php $_template = new Smarty_Internal_Template("index/frame/left.tpl", $_smarty_tpl->smarty, $_smarty_tpl, $_smarty_tpl->cache_id, $_smarty_tpl->compile_id, null, null);
 echo $_template->getRenderedTemplate();?><?php $_template->updateParentVariables(0);?><?php unset($_template);?>


<script type="text/javascript">
function reSetIframe(){
    var iframe = document.getElementById("iframeId");
    try{
        var bHeight = iframe.contentWindow.document.body.scrollHeight;
        var dHeight = iframe.contentWindow.document.documentElement.scrollHeight;
        var height = Math.max(bHeight, dHeight);
        iframe.height = height;
    }catch (ex){}
}
$(function(){
	// default selected menu link
	ChangeNav("top_1", "/common/");
});
</script>

<div class="right">
	<div class="main">
		<iframe id="main" name="main" frameborder="0" src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
common/" onload="javascript:reSetIframe();"></iframe>
		<div sytle="clear:both"></div>
	</div>
</div>
<!-- right end -->

<!--
<div class="qucikmenu" id="qucikmenu">
	<ul>
		<li><a href="javascript:;" target="main">1</a></li>
		<li><a href="javascript:;" target="main">2</a></li>
		<li><a href="javascript:;" target="main">3</a></li>
	</ul>
</div>
-->
<!-- qucikmenu end -->

</body>
</html>