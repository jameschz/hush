<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>iHush Tracking Console System</title>
<link href="{$_root}css/frame.css" rel="stylesheet" type="text/css" />
<script src="{$_root}js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="{$_root}js/frame.js" language="javascript" type="text/javascript"></script>
</head>

<body class="showmenu" scroll="no">

<div class="pagemask"></div>

<iframe class="iframemask"></iframe>

{include file="index/frame/head.tpl"}

{include file="index/frame/left.tpl"}

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
		<iframe id="main" name="main" frameborder="0" src="{$_root}common/" onload="javascript:reSetIframe();"></iframe>
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