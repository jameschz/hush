{include file="index/frame/head.tpl"}

<div class="ax_nr">
	<div class="ax_nr_left">
		<div class="ax_nr_left_nr" >
			<div class="ax_nr_left_bt"><a href="#"><i class="icon icon-shrink"></i></a></div>
			{foreach $appList as $k1 => $v1}
			<div class="ax_nr_left_nav">
				<div class="menu_lv1 ax_nr_left_nav_bt" id="menu_lv1_{$k1}"><font>►</font>{$v1.name}</div>
				<div class="ax_nr_left_nav_nr" style="display:none">
				{foreach $v1.list as $k2 => $v2}
					<p class="menu_lv2" id="menu_lv2_{$k2}" menu_id="{$k2}"><i class="{$v2.icon}"></i>{$v2.name}</p>
				{/foreach}
				</div>
			</div>
			{/foreach}
			<div class="ax_h_50"></div>
		</div>
	</div>
	<div class="ax_nr_right">
		{foreach $appList as $k1 => $v1}
			{foreach $v1.list as $k2 => $v2}
			<div class="menu_div ax_nr_right_nav" id="menu_div_{$k2}" style="display:none">
				<div class="ax_nr_right_nav_bt">{$v2.name}</div>
				<div class="ax_nr_right_nav_nr">
				{foreach $v2.list as $k3 => $v3}
					<div class="menu_lv3 ax_nr_right_nav_nr_nav" id="menu_lv3_{$k3}" menu_url="{$v3.path}">{$v3.name}</div>
				{/foreach}
				</div>
			</div>
			{/foreach}
		{/foreach}
		<div class="ax_nr_dj">
			<div class="ax_nr_dj_left"><i class="icon icon-paragraph-justify"></i></div>
		</div>
		<div class="ax_nr_dj_02" style="display: none;">
			<div class="ax_nr_dj_right"><i class="icon icon-paragraph-justify"></i></div>
		</div>
		<div class="ax_nr_right_nr">
			<iframe id="menu_box" frameborder="0" src="{$_root}acl/welcome" style="width:100%;height:100%;"></iframe>
		</div>
	</div>
</div>
{*
<div id="fresh_btn" class="icon icon-loop2 ax_fresh_btn"></div>
<script type="text/javascript">
$(function(){
	$('#fresh_btn').click(function(){
		do_fresh_msg();
		do_fresh_box();
	});
});
</script>
*}
{include file="index/frame/foot.tpl"}