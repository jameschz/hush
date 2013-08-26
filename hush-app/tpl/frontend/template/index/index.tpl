{include file="frame/head.tpl"}

	<img src="{$_root}img/logo.gif" />
	
	<div style="padding:8px 0px 8px 0px"><hr/></div>
	
	<h1 style="font-size:14pt">{$welcome}</h1>
	
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_star.png" class="icon" /> ZendFramework 和 Smarty 的完美结合（MVC/S）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 优化的 ZendFramework url routing（效率提升 N 倍）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 完善的 Full Stack 前后台松耦合框架结构（带调试框架）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 优化数据库连接，支持主从分离，分库分表策略</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 强大的 RBAC 权限控制系统（可扩展）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 整合 BPM 流程管理框架（可编程） </li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 多进程，消息处理（用于 CLI） </li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Framework Performance Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_arrow_right.png" class="icon" /> 路由模式 : <a href="/?debug=time">执行时间</a></li>
		<li><img src="{$_root}img/icon_arrow_right.png" class="icon" /> 传统模式 : <a href="/app/index.php?debug=time">执行时间</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Url Mapping Engine Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_round.png" class="icon" /> 普通映射 : <a href="/test/mapping?debug=time">/test/mapping</a></li>
		<li><img src="{$_root}img/icon_round.png" class="icon" /> 分页演示 : <a href="/test/p/1?debug=time">/test/p/*</a></li>
		<li><img src="{$_root}img/icon_round.png" class="icon" /> 模糊匹配 : <a href="/test/*?debug=time">/test/*</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Sharding Database Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_round.png" class="icon" /> DB Mysql : <a href="/testDb/mysqlPage?debug=sql">/testDb/mysqlPage</a></li>
		<li><img src="{$_root}img/icon_round.png" class="icon" /> DB Mysql : <a href="/testDb/mysqlShard?debug=sql">/testDb/mysqlShard</a></li>
		<li><img src="{$_root}img/icon_round.png" class="icon" /> DB Mongo : <a href="/testDb/mongoShard?debug=time">/testDb/mongoShard</a></li>
	</ul>

{include file="frame/foot.tpl"}