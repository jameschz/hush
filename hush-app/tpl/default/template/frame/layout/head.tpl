<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1">
<title></title>
<link rel="stylesheet" href="{$_host_s}/css/layout.css">
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