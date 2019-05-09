{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 欢迎页面
</div>

<div class="mainbox">
	
	<h1 style="font-size:14pt">Hello, {$_admin.name}！</h1>
	
	<ul style="margin-top:10px">
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 您的角色：
		{foreach $_admin.priv as $priv}<img src="{$_root}img/icon_man.png" class="icon" /> {$priv} {/foreach}</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 系统时间：{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}</li>
	</ul>

</div>

{include file="frame/foot.tpl"}