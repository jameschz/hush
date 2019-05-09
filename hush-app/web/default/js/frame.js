//是否折叠其他菜单
var close_others = true;

//顶部菜单最大个数
var top_menu_max = 6;

//保存顶部菜单游标
var top_menu_cur = 0;

//保存顶部菜单数量
var top_menu_num = 0;

//保存当前链接名称
var current_link = '';

//domready
$(function(){

	//快捷菜单
	bindQuickMenu();
	
	//菜单绑定
	bindAdminMenu();
	
	//左侧菜单开关
	LeftMenuToggle();
	
	//顶部菜单滑动
	TopMenuSlide(0);
	
	//取消菜单链接虚线
	$(".head").find("a").click(function(){$(this).blur()});
	$(".menu").find("a").click(function(){$(this).blur()});

}).keydown(function(event){//快捷键

	if(event.keyCode == 116){
		url = $("#main").attr("src");
		main.location.href = url;
		return false;
	}
	
	if(event.keyCode == 27){
		$("#qucikmenu").slideToggle("fast")
	}
	
});

//快捷菜单（暂不用）
function bindQuickMenu()
{
	$("#ac_qucikmenu").bind("mouseenter",function(){
		$("#qucikmenu").slideDown("fast");
	}).dblclick(function(){
		$("#qucikmenu").slideToggle("fast");
	}).bind("mouseleave",function(){
		hidequcikmenu = setTimeout('$("#qucikmenu").slideUp("fast");',700);
		$(this).bind("mouseenter",function(){clearTimeout(hidequcikmenu);});
	});
	
	$("#qucikmenu").bind("mouseleave",function(){
		hidequcikmenu = setTimeout('$("#qucikmenu").slideUp("fast");',700);
		$(this).bind("mouseenter",function(){clearTimeout(hidequcikmenu);});
	}).find("a").click(function(){
		$(this).blur();
		$("#qucikmenu").slideUp("fast");
		$("#ac_qucikmenu").text($(this).text());
	});
}

//菜单绑定逻辑（执行一次）
function bindAdminMenu()
{
	// top menu click action
	$("#nav").find("a").click(function(){
		ChangeNav($(this).attr("_for"));
	});

	// left menu click action
	$("#menu").find("dt").click(function(){
		if (close_others) {
			$("#menu").find("dt").css("background-position","left top");
			$("#menu").find("dt").next("dd").slideUp("fast");
		}
		click_dt = $(this);
		click_dd = $(this).next("dd");
		if(click_dd.css("display") == "none"){
			click_dd.slideDown("fast");
			click_dt.css("background-position","left bottom");
		}else{
			click_dd.slideUp("fast");
			click_dt.css("background-position","left top");
		}
	});

	$("#menu dd ul li a").click(function(){
		$(this).addClass("thisclass").blur().parents("#menu").find("ul li a").not($(this)).removeClass("thisclass");
		current_link = $(this).attr("href");
	});
}

//菜单选择逻辑（每次执行）
function ChangeNav(nav)
{
	// init top menus
	$("#nav").find("a").removeClass("thisclass");
	$("#nav").find("a[_for='"+nav+"']").addClass("thisclass").blur();
	$("body").attr("class","showmenu");
	
	// init left menus
	$("#menu").find("div[class^=items]").hide(); // hide all first then show menu
	$("#menu").find(".items_"+nav).find("dl dd").find("ul li a").removeClass("thisclass");
	$("#menu").find(".items_"+nav).css("display", "inline"); // fix bug in firefox
	
	// find current menu link
	link_name = arguments[1] ? arguments[1] : current_link;
	curr_link = $("#menu").find(".items_"+nav).find("dd ul li a[href='"+link_name+"']");
	curr_link.addClass("thisclass").blur();
	current_link = link_name; // saved current link
	
	// hide other links
	if (close_others) {
		$("#menu").find("dt").css("background-position","left top");
		$("#menu").find("dt").next("dd").hide();
	}

	// show current link
	curr_div = null;
	if (curr_link) {
		curr_dl = curr_link.parent().parent().parent().parent();
		curr_dt = curr_dl.children("dt");
		curr_dd = curr_dl.children("dd");
		curr_dd.slideDown("fast");
		curr_dt.css("background-position","left bottom");
		curr_div = curr_dl.parent();
	}
	
	// show first menu default
	if (!curr_div || !curr_div.attr('class')) {
		curr_dl = $($("div.items_"+nav)[0]).find('dl');
		curr_dt = curr_dl.children("dt");
		curr_dd = curr_dl.children("dd");
		curr_dd.slideDown("fast");
		curr_dt.css("background-position","left bottom");
		curr_div = curr_dl.parent();
	}
}

//左侧菜单开关（隐藏/显示菜单）
function LeftMenuToggle()
{
	$("#togglemenu").click(function(){
		if($("body").attr("class") == "showmenu"){
			$("body").attr("class","hidemenu");
			$(this).html("[显示菜单]");
		}else{
			$("body").attr("class","showmenu");
			$(this).html("[隐藏菜单]");
		}
	});
}

//顶部菜单滑动（通过top_menu_max控制）
function TopMenuSlide(direction)
{
	// get top menu number
	if (top_menu_num == 0) {
		top_menu_num = $("#nav").find("li").size();
		
	}
	
	// do not need slide top menu
	if (top_menu_max == 0 || top_menu_max >= top_menu_num) {
		return ;
	}
	
	// init top menu
	if (direction == 0) {
		$("#nav").find("ul").prepend("<li><a onclick='javascript:TopMenuSlide(-1);' style='width:20px;cursor:pointer;'>&lt;</a></li>");
		$("#nav").find("ul").append("<li><a onclick='javascript:TopMenuSlide(1);' style='width:20px;cursor:pointer;'>&gt;</a></li>");
		top_menu_cur++;
		menu_cur_s = 0;
		menu_cur_e = top_menu_num + 2 - 1;
		top_menus = $("#nav").find("li");
		top_menus.hide();
		top_menus.each(function(i){
			if (i >= top_menu_cur && i < (top_menu_cur + top_menu_max)) {
				$(this).show();
			}
			if (i == menu_cur_s || i == menu_cur_e) {
				$(this).show();
			}
		});
		return ;
	}
	
	// menu slide logic
	else {
		
		menu_cur_s = 0;
		menu_cur_e = top_menu_num + 2 - 1;
		
		if ((direction < 0 && top_menu_cur == 1) || 
			(direction > 0 && top_menu_cur + top_menu_max >= menu_cur_e)) {
			return ;
		}
		
		hide_menu_id = -1;
		show_menu_id = -1;
		
		if (direction > 0) {
			hide_menu_id = top_menu_cur;
			show_menu_id = top_menu_cur + top_menu_max;
			top_menu_cur++;
		} else if (direction < 0) {
			hide_menu_id = top_menu_cur + top_menu_max - 1;
			show_menu_id = top_menu_cur - 1;
			top_menu_cur--;
		} else {
			return ;
		}
		
		top_menus = $("#nav").find("li");
		top_menus.each(function(i){
			if (i == hide_menu_id) $(this).hide("fast");
			if (i == show_menu_id) $(this).show("fast");
		});
	}
}
