<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title></title>
<link href="{$_host_s}/css/font.css" rel="stylesheet" type="text/css" />
<link href="{$_host_s}/css/main.css?v={$_ver}" rel="stylesheet" type="text/css" />
{foreach $cssList as $css}<link href="{$_host_s}/{$css}?v={$_ver}" rel="stylesheet" type="text/css" />
{/foreach}
<script src="{$_host_s}/js/json.js" type="text/javascript"></script>
<script src="{$_host_s}/js/string.js" type="text/javascript"></script>
<script src="{$_host_s}/js/jquery.js" type="text/javascript"></script>
<script src="{$_host_s}/js/jquery.ui.js" type="text/javascript"></script>
<script src="{$_host_s}/js/block.js" type="text/javascript"></script>
<script src="{$_host_s}/js/james.js" type="text/javascript"></script>
<script src="{$_host_s}/app/layer/layer.js" type="text/javascript"></script>
{foreach $jsList as $js}<script src="{$_host_s}/{$js}?v={$_ver}" language="javascript" type="text/javascript"></script>
{/foreach}
</head>
<body>
<div class="main">