/*登录*/
$(document).ready(function (){ 
	setTimeout(function(){
		$('.ax_loding_logo img').animate({
			opacity:1,width:120},{
			easing:'easeInQuint',
			duration: 500,
			complete:function(){}
		});
	},100);
});
/*登录 end*/

/*顶部弹出*/
$(function(){
	$('#ax_top_cpfw').click(function (event) {  
		//取消事件冒泡  
		event.stopPropagation();
		if ($('#ax_top_cpfw .ax_tc_cpfw').is(':hidden')) {
			$("#ax_top_cpfw").addClass('ax_top_left_open');   
			$("#ax_top_cpfw font").text('▲');
			$("#ax_top_cpfw .ax_tc_cpfw").css("display", "flex");
		} else {
			
			$("#ax_top_cpfw").removeClass('ax_top_left_open');   
			$("#ax_top_cpfw font").text('▼');
			$("#ax_top_cpfw .ax_tc_cpfw").css("display", "none");
		}
		return false;
	});  
	$(document).click(function(event){
		var _con = $('#ax_top_cpfw');   // 设置目标区域
		if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
			$("#ax_top_cpfw").removeClass('ax_top_left_open');   
			$("#ax_top_cpfw font").text('▼');
			$("#ax_top_cpfw .ax_tc_cpfw").css("display", "none");
		}
	});
});
$(function(){
	$('#ax_top_grzx').click(function (event) {  
		//取消事件冒泡  
		event.stopPropagation();
		if ($('#ax_top_grzx .ax_tc_cpfw').is(':hidden')) {
			$("#ax_top_grzx").addClass('ax_top_left_open');   
			$("#ax_top_grzx font").text('▲');
			$("#ax_top_grzx .ax_tc_cpfw").css("display", "flex");
		} else {
			$("#ax_top_grzx").removeClass('ax_top_left_open');   
			$("#ax_top_grzx font").text('▼');
			$("#ax_top_grzx .ax_tc_cpfw").css("display", "none");
		}
		return false;
	});  
	$(document).click(function(event){
		var _con = $('#ax_top_grzx');   // 设置目标区域
		if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
			$("#ax_top_grzx").removeClass('ax_top_left_open');   
			$("#ax_top_grzx font").text('▼');
			$("#ax_top_grzx .ax_tc_cpfw").css("display", "none");
		}
	});
});
$(function(){
	$('#ax_top_bzzx').click(function (event) {  
		//取消事件冒泡  
		event.stopPropagation();
		if ($('#ax_top_bzzx .ax_tc_cpfw').is(':hidden')) {
			$("#ax_top_bzzx").addClass('ax_top_left_open');   
			$("#ax_top_bzzx font").text('▲');
			$("#ax_top_bzzx .ax_tc_cpfw").css("display", "flex");
			$("#ax_top_bzzx .ax_tc_cpfw_01").css("display", "inline");
			$("#ax_top_bzzx .ax_tc_cpfw_01").css("width", "auto");
		} else {
			$("#ax_top_bzzx").removeClass('ax_top_left_open');   
			$("#ax_top_bzzx font").text('▼');
			$("#ax_top_bzzx .ax_tc_cpfw").css("display", "none");
		}
		return false;
	});  
	$(document).click(function(event){
		var _con = $('#ax_top_bzzx');   // 设置目标区域
		if(!_con.is(event.target) && _con.has(event.target).length === 0){ // Mark 1
			$("#ax_top_bzzx").removeClass('ax_top_left_open');   
			$("#ax_top_bzzx font").text('▼');
			$("#ax_top_bzzx .ax_tc_cpfw").css("display", "none");
		}
	});
});
/*顶部弹出 end*/

function selMenuLv1 (menu_btn_id)
{
	var menu_btn = $('#' + menu_btn_id);
	var menu_dir = arguments[1] || '';
	// 菜单滑动效果
	var icon = menu_btn.find('font');
	var next = menu_btn.next();
	if (menu_dir == 'down') {
		icon.text('▼');
		next.slideDown();  
	} else if (menu_dir == 'up') {
		icon.text('►');
		next.slideUp();
	} else {
		if (next.is(':hidden')) {
			icon.text('▼');
			next.slideDown();  
		} else {
			icon.text('►');
			next.slideUp();
		}
	}
}

function selMenuLv2 (menu_btn_id)
{
	var menu_btn = $('#' + menu_btn_id);
	var menu_id = menu_btn.attr('menu_id');
	// 左侧菜单样式
	$('.menu_lv2').removeClass('ax_nr_left_nav_nr_h');
	menu_btn.addClass('ax_nr_left_nav_nr_h');
	// 右侧菜单操作
	if (menu_id) {
		// 隐藏右侧菜单
		$('.menu_div').hide();
		// 显示相关菜单
		var menu_div = $('#menu_div_' + menu_id);
		if (menu_div.is(':hidden')) {
			menu_div.show();
			// 找到第一个菜单项并选择
			selMenuLv3(menu_div.find('.menu_lv3').attr('id'));
		}
	}
}

function selMenuLv3 (menu_btn_id)
{
	var menu_btn = $('#' + menu_btn_id);
	var menu_url = menu_btn.attr('menu_url');
	// 右侧菜单样式
	$('.menu_lv3').removeClass('ax_nr_right_nav_nr_h');
	menu_btn.addClass('ax_nr_right_nav_nr_h');
	// 进入菜单功能页
	$('#menu_box').attr('src', menu_url);
}

function changeDivHeight () {               
	var heig = $(window).height(); 
	$(".ax_nr_left").css("height", heig); 
	$(".ax_nr_right").css("height", heig-1); 
	$(".ax_loding").css("height", heig); 
}

function goMenu (url) {
	var menuLv3 = $('div[menu_url="'+url+'"]');
	if (menuLv3.length > 0) {
		menuLv3Id = menuLv3.attr('id');
		menuLv2Id = menuLv3.parent().parent().attr('id').replace('menu_div_','');
		var menuLv2 = $('p[id="menu_lv2_'+menuLv2Id+'"]');
		if (menuLv2.length > 0) {
			menuLv2Id = menuLv2.attr('id');
			var menuLv1 = menuLv2.parent().prev();
			if (menuLv1.length > 0) {
				menuLv1Id = menuLv1.attr('id');
			    selMenuLv1(menuLv1Id, 'down');
			    selMenuLv2(menuLv2Id);
			    selMenuLv3(menuLv3Id);
			}
		}
	}
}

$(function(){
	// 收放效果
	$('.menu_lv1').click(function(){
		selMenuLv1($(this).attr('id'));
	});
	// 菜单选择
	$('.menu_lv2').click(function(){
		selMenuLv2($(this).attr('id'));
	});
	// 菜单选择
	$('.menu_lv3').click(function(){
		selMenuLv3($(this).attr('id'));
	});
	// 左侧菜单收放
	$('.ax_nr_left_bt').click(function(){
		$(".ax_nr_left").toggleClass('wi');
		$(".ax_nr_left_bt").toggleClass('wi');
		$(".ax_nr_right").toggleClass('wi');
		var icon = $(this).find('i');
		if (icon.hasClass('icon-shrink')) {
			icon.removeClass('icon-shrink').addClass('icon-enlarge');
		} else {
			icon.removeClass('icon-enlarge').addClass('icon-shrink');
		}
	});
	// 右侧菜单收放
	$(".ax_nr_dj").click(function () {
		$(".ax_nr_dj").css("display", "none");
		$(".ax_nr_dj_02").css("display", "block");
//		$(".ax_nr_right_nav").css("display", "none");
		$(".ax_nr_right_nr").css("left", "180");
		setTimeout(function(){
			$('.ax_nr_right_nr').animate({
				left: 0},{
				easing:'easeOutBounce',
				duration: 500,
				complete:function(){}
			});
		},100);
	});
	$(".ax_nr_dj_02").click(function () {
		$(".ax_nr_dj").css("display", "block");
		$(".ax_nr_dj_02").css("display", "none");
//		$(".ax_nr_right_nav").css("display", "block");
		$(".ax_nr_right_nr").css("left", "0");
		setTimeout(function(){
			$('.ax_nr_right_nr').animate({
				left: 180},{
				easing:'jswing',
				duration: 200,
				complete:function(){}
			});
		},100); 
	});
	// 高度自动伸缩
	changeDivHeight();
});

window.onresize=function(){  
	changeDivHeight();  
}  
