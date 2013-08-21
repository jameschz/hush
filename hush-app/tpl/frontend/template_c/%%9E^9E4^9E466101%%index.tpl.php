<?php /* Smarty version 2.6.25, created on 2013-08-01 17:31:05
         compiled from index%5Cindex.tpl */ ?>
<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frame/head.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>

	<img src="<?php echo $this->_tpl_vars['_root']; ?>
img/logo.gif" />
	
	<div style="padding:8px 0px 8px 0px"><hr/></div>
	
	<h1 style="font-size:14pt"><?php echo $this->_tpl_vars['welcome']; ?>
</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> ZendFramework 和 Smarty 的完美结合（MVC）</li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 优化的 ZendFramework Url Mapping 机制（比 ZF 快 N 倍）</li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 完善的 Full Stack 前后台框架结构（带调试框架）</li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 支持数据库连接池，负载均衡，分库分表策略</li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 强大的 RBAC 权限控制系统（可扩展）</li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 整合 BPM 流程管理框架（可编程） </li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_star.png" class="icon" /> 松耦合，易安装，易扩展</li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Framework Performance Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_arrow_right.png" class="icon" /> 路由模式 : <a href="/?debug=time">执行时间</a></li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_arrow_right.png" class="icon" /> 传统模式 : <a href="/app/index.php?debug=time">执行时间</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Url Mapping Engine Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_round.png" class="icon" /> 普通映射 : <a href="/test/mapping?debug=time">/test/mapping</a></li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_round.png" class="icon" /> 分页演示 : <a href="/test/p/1?debug=time">/test/p/*</a></li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_round.png" class="icon" /> 模糊匹配 : <a href="/test/*?debug=time">/test/*</a></li>
	</ul>
	
	<h1 style="font-size:14pt">Hush Sharding Database Test :</h1>
	
	<ul style="margin:10px">
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_round.png" class="icon" /> DB Mysql : <a href="/testDb/mysqlShard?debug=sql">/testDb/mysqlShard</a></li>
		<li><img src="<?php echo $this->_tpl_vars['_root']; ?>
img/icon_round.png" class="icon" /> DB Mongo : <a href="/testDb/mongoShard?debug=time">/testDb/mongoShard</a></li>
	</ul>

<?php $_smarty_tpl_vars = $this->_tpl_vars;
$this->_smarty_include(array('smarty_include_tpl_file' => "frame/foot.tpl", 'smarty_include_vars' => array()));
$this->_tpl_vars = $_smarty_tpl_vars;
unset($_smarty_tpl_vars);
 ?>