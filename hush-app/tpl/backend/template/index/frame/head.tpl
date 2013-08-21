<div class="head">
	<div class="top_logo">
		<img src="{$_root}img/logo.gif"  alt="iHush Track System" />
	</div>
	<div class="top_link">
		<ul>
			<li class="welcome">欢迎您, {$_admin.name} {if $_admin.sa}(sa){/if}</li>
			<li class="menuact"><a href="#" id="togglemenu">[隐藏菜单]</a></li>
			<li><a href="{$_root}auth/logout" target="_top">[退出]</a></li>
		</ul>
		<!--
		<div class="quick">
			<a href="#" class="ac_qucikmenu" id="ac_qucikmenu">1</a>
			<a href="#" class="ac_qucikadd" id="ac_qucikadd">2</a>
		</div>
		-->
	</div>
	<div class="nav" id="nav">
		<ul>
			{foreach $appList as $topAppList}
				<li><a {if $topAppList@first}class="thisclass"{/if} href="javascript:;" _for="top_{$topAppList.id}" target="main">{$topAppList.name}</a></li>
			{/foreach}
		</ul>
	</div>
</div>
<!-- header end -->