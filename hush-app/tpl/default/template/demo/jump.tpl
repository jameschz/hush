{include file="frame/head.tpl"}

<div style="display-inline:block;">
    <a style="margin:10px;display:block;" id="jump_to_home" >通过设置菜单跳转到“布局页面范例”</a>
    <a style="margin:10px;display:block;" id="jump_to_list" >通过JS直接跳转到“列表页面范例”</a>
</div>

<script type="text/javascript">
$(function(){
	// 通过选择菜单跳转
	$('#jump_to_home').click(function(){
	    parent.selMenuLv1('{$menu_res.0}','down');
	    parent.selMenuLv2('{$menu_res.1}');
	    parent.selMenuLv3('{$menu_res.2}');
	});
	// 通过JS方法直接跳转
	$('#jump_to_list').click(function(){
		parent.goMenu('/demo/list');
	});
});
</script>

{include file="frame/foot.tpl"}