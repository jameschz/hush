{include file="frame/head.tpl"}

<link href="{$_root}css/jquery.time.css" rel="stylesheet" type="text/css" />
<link href="{$_root}css/jquery.table.css" rel="stylesheet" type="text/css" />
<script type="text/javascript" src="{$_root}js/jquery.time.js"></script>
<script type="text/javascript" src="{$_root}js/jquery.table.js"></script>
<script type="text/javascript" src="{$_root}js/block.js"></script>

<div class="maintop">
	<img src="{$_root}img/icon_arrow_right.png" class="icon" /> <b>测试应用</b>
</div>

<div class="mainbox">
	
	<h1 style="font-size:14pt">{$welcome}</h1>
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_star.png" class="icon" /> ZendFramework 和 Smarty 的完美结合（MVC）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 优化的 ZendFramework Url Mapping 机制（比 ZF 快 N 倍）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 完善的 Full Stack 前后台框架结构（带调试框架）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 提供多数据库连接池，多数据库服务器负载均衡</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 强大的 ACL 权限控制系统（可扩展）</li>
		<li><img src="{$_root}img/icon_star.png" class="icon" /> 易安装，易配置，易扩展</li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Framework Debug Test :</h1>
	<ul style="margin:10px">
		<li><img src="{$_root}img/icon_arrow_right.png" class="icon" /> 调试页面 : <a href="?debug=time">执行时间</a></li>
		<li><img src="{$_root}img/icon_arrow_right.png" class="icon" /> 调试全部 : <a href="?debug=time,sql">时间&SQL</a></li>
	</ul>
	
	<h1 style="font-size:14pt">JQuery UI Demo :</h1>
	<div id="timedebug"></div>
	<div id="jquery-ui-demos">
		<div style="margin:10px;">
			<table>
				<tr>
					<td>选择日期：</td>
					<td><input type="text" id="datepicker"></td>
				</tr>
				<tr>
					<td>选择时间：</td>
					<td><input type="text" id="timepicker"></td>
				</tr>
				<tr>
					<td>选择日期+时间：</td>
					<td><input type="text" id="datetimepicker"></td>
				</tr>
			</table>
		</div>
		<script type="text/javascript">
		$(function() {
			$("#datepicker").datepicker({ dateFormat: 'yy-mm-dd' });
			$("#timepicker").timepicker({ timeFormat: 'hh:mm:ss' });
			$("#datetimepicker").datetimepicker({ dateFormat: 'yy-mm-dd' });
		});
		</script>
	</div>
	
	<h1 style="font-size:14pt">Table Sorter Demo :</h1>
	<div id="jquery-ui-demos">
		<div style="margin:10px;">
			<table class="tablesorter">
				<thead>
					<tr>
						<td>日期</td>
						<th>收入</th>
						<th>利润</th>
						<td>操作</td>
					</tr>
				</thead>
				<tbody>
					<tr>
						<td>2013-01-01</td>
						<td>41305.6</td>
						<td>28961.2</td>
						<td><a href="javascript:$.overlay.show($('#detail_1'));">明细</a></td>
					</tr>
					<tr>
						<td>2013-01-02</td>
						<td>89714.3</td>
						<td>60215.4</td>
						<td><a href="javascript:$.overlay.show($('#detail_2'));">明细</a></td>
					</tr>
					<tr>
						<td>2013-01-03</td>
						<td>30154.5</td>
						<td>21047.7</td>
						<td><a href="javascript:$.overlay.show($('#detail_3'));">明细</a></td>
					</tr>
				</tbody>
			</table>
			<!-- 以下是隐藏弹出层 -->
			<div id="detail_1" class="disn">
				<div class="windownav">
					<img src="{$_root}img/icon_wrong.png" class="close" onclick="javascript:$.overlay.close(0)"/>
					<img src="{$_root}img/icon_arrow_right.png" class="icon" /> <b>2013-01-01 明细</b>
				</div>
				<div style="padding:50px;">2013-01-01 明细内容</div>
			</div>
			<div id="detail_2" class="disn">
				<div class="windownav">
					<img src="{$_root}img/icon_wrong.png" class="close" onclick="javascript:$.overlay.close(0)"/>
					<img src="{$_root}img/icon_arrow_right.png" class="icon" /> <b>2013-01-02 明细</b>
				</div>
				<div style="padding:50px;">2013-01-02 明细内容</div>
			</div>
			<div id="detail_3" class="disn">
				<div class="windownav">
					<img src="{$_root}img/icon_wrong.png" class="close" onclick="javascript:$.overlay.close(0)"/>
					<img src="{$_root}img/icon_arrow_right.png" class="icon" /> <b>2013-01-03 明细</b>
				</div>
				<div style="padding:50px;">2013-01-03 明细内容</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function() {
			$(".tablesorter").tablesorter();
		});
		</script>
	</div>
	
</div>

{include file="frame/foot.tpl"}