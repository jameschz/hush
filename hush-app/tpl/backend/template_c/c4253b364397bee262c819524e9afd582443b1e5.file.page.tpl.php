<?php /* Smarty version Smarty3-b8, created on 2013-04-24 09:53:36
         compiled from "D:\workspace\hush-framework\hush-app\tpl\backend\template\frame/page.tpl" */ ?>
<?php /*%%SmartyHeaderCode:2860551773b202178d0-68762927%%*/if(!defined('SMARTY_DIR')) exit('no direct access allowed');
$_smarty_tpl->decodeProperties(array (
  'file_dependency' => 
  array (
    'c4253b364397bee262c819524e9afd582443b1e5' => 
    array (
      0 => 'D:\\workspace\\hush-framework\\hush-app\\tpl\\backend\\template\\frame/page.tpl',
      1 => 1357552553,
    ),
  ),
  'nocache_hash' => '2860551773b202178d0-68762927',
  'function' => 
  array (
  ),
  'has_nocache_code' => false,
)); /*/%%SmartyHeaderCode%%*/?>
<?php if ($_smarty_tpl->getVariable('paging')->value){?>
<div class="pagelist" style="float:right">
	<span>共 <?php echo $_smarty_tpl->getVariable('paging')->value['totalPage'];?>
 页 / <?php echo $_smarty_tpl->getVariable('paging')->value['totalNum'];?>
 条记录 </span><span class="indexPage"><?php echo $_smarty_tpl->getVariable('paging')->value['prevStr'];?>
</span><?php echo $_smarty_tpl->getVariable('paging')->value['pageStr'];?>
<span class="nextPage"><?php echo $_smarty_tpl->getVariable('paging')->value['nextStr'];?>
</span> 
</div>
<?php }?>