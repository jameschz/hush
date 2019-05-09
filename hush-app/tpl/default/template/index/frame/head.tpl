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
<link href="{$_host_s}/css/hush.css" rel="stylesheet" type="text/css" />
<link href="{$_host_s}/css/font.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$_host_s}/js/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="{$_host_s}/js/jquery.easing.min.js"></script>
<script type="text/javascript" src="{$_host_s}/js/hush.js"></script>
<script type="text/javascript">
function go_home () {
    selMenuLv1('menu_lv1_1','down');
    selMenuLv2('menu_lv2_2');
    selMenuLv3('menu_lv3_3');
}
function go_message () {
    
}
function go_helpcenter () {
    
}
function do_fresh_msg () {
	$.post('/index/msgnum', {}, function(json){
		if (json.err == 0) {
			$('#msg_num_box').show().html(json.data.msg_num);
		} else {
			$('#msg_num_box').hide().html(0);
		}
	}, 'json');
}
function do_fresh_box () {
    var iframe_src = $('#menu_box').attr('src');
    var iframe_sp = (iframe_src.indexOf("?") != -1) ? '&' : '?';
    var iframe_src_new = iframe_src + iframe_sp + 't=' + Math.floor(Math.random()*1000 + 1);
    $('#menu_box').attr('src', iframe_src_new);
}
$(function(){
	go_home();
	do_fresh_msg();
});
</script>
</head>
<body style="overflow:hidden;">
	<div class="ax_top">
		<div class="ax_top_left">
			<div class="ax_top_left_logo"><img src="{$_host_s}/img/logo.png"></div>
			<div class="ax_top_left_sy ax_top_left_dj"><a href="/">控制台首页</a></div>
			<div class="ax_top_left_hz ax_top_left_dj" id="ax_top_cpfw">
			</div>
		</div>
		<div class="ax_top_right">
            {if $_shops}{*商户后台*}
            <div class="ax_top_right_01 ax_top_left_dj" id="ax_top_grzx">
                <b id="ax_cur_shop_box" data-id="{$_shop.id}">{$_shop.sname_s}</b><font>▼</font>
                <div class="ax_tc_cpfw" style="display:none;">
                    <div class="ax_tc_cpfw_01">
                        {foreach $_shops as $k => $v}
                        <p class="ax_tc_cpfw_nr ax_all_shop_list" data-id="{$v.id}" onclick="javascript:select_shop({$v.id});"><i class="icon icon-play3"></i>{$v.sname_s}</p>
                        {/foreach}
                        <p class="ax_tc_cpfw_nr" onclick="javascript:location.href='/auth/logout';"><i class="icon icon-switch"></i>注销登录帐号</p>
                    </div>
                </div>
            </div>
            {else}{*超级后台*}
			<div class="ax_top_right_01 ax_top_left_dj" id="ax_top_grzx">
				<b>{if $_admin.sa}超级管理员{else}{$_admin.name}{/if}</b><font>▼</font>
				<div class="ax_tc_cpfw" style="display:none;">
					<div class="ax_tc_cpfw_01">
						<p class="ax_tc_cpfw_nr" onclick="javascript:location.href='/auth/logout';"><i class="icon icon-switch"></i>注销登录帐号</p>
					</div>
				</div>
			</div>
            {/if}
			<div class="ax_top_right_01 ax_top_left_dj" onclick="javascript:go_helpcenter();">帮助中心</div>
			{if $_shops}{*商户后台*}
            <div class="ax_top_right_01 ax_top_left_dj" onclick="javascript:go_home();">
                <i class="icon icon-mail2"></i><span id="msg_num_box" style="display:none;"></span>
            </div>
            {else}{*超级后台*}
            <div class="ax_top_right_01 ax_top_left_dj" onclick="javascript:go_message();">
                <i class="icon icon-mail2"></i><span id="msg_num_box" style="display:none;"></span>
            </div>
            {/if}
		</div>
	</div>