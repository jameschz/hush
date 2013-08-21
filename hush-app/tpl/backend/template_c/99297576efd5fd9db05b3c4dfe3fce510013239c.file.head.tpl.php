<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:49:16
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\frame/head.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2840751773a1ce8b4d8-58246836%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    '99297576efd5fd9db05b3c4dfe3fce510013239c' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\frame/head.tpl',
      1 => 1357552553,
    ),
  ),
  'nocache_hash' => '2840751773a1ce8b4d8-58246836',
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
css/main.css" rel="stylesheet" type="text/css" />
<link href="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
css/jquery.css" rel="stylesheet" type="text/css" />
<?php  $_smarty_tpl->tpl_vars['css'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('cssList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['css']->key => $_smarty_tpl->tpl_vars['css']->value){
?><link href="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
<?php echo $_smarty_tpl->getVariable('css')->value;?>
" rel="stylesheet" type="text/css" />
<?php }} ?>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/json.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/string.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/jquery.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/jquery.ui.js" language="javascript" type="text/javascript"></script>
<script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
js/ihush.js" language="javascript" type="text/javascript"></script>
<?php  $_smarty_tpl->tpl_vars['js'] = new Smarty_Variable;
 $_from = $_smarty_tpl->getVariable('jsList')->value; if (!is_array($_from) && !is_object($_from)) { settype($_from, 'array');}
if (count($_from) > 0){
    foreach ($_from as $_smarty_tpl->tpl_vars['js']->key => $_smarty_tpl->tpl_vars['js']->value){
?><script src="<?php echo $_smarty_tpl->getVariable('_root')->value;?>
<?php echo $_smarty_tpl->getVariable('js')->value;?>
" language="javascript" type="text/javascript"></script>
<?php }} ?>
</head>

<body>

<div class="main">
