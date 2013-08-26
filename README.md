#Hush Framework

##Summary

> Powerful and Full-stack web application framework for PHP

> Based on Zend Framework and Smarty template engine!

> By -- James.Huang (黄隽实) 

##Features

1. ZendFramework 和 Smarty 的完美结合（MVC/S）

2. 优化的 ZendFramework url routing（效率提升 N 倍）

3. 完善的 Full Stack 前后台松耦合框架结构（带调试框架）

4. 优化数据库连接，支持主从分离，分库分表策略

5. 强大的 RBAC 权限控制系统（可扩展）

6. 整合 BPM 流程管理框架（可编程）

7. 多进程，消息处理（用于 CLI） 

##Quick Guide

####1> 安装框架源码

进入目录执行命令：

    git clone https://github.com/jameschz/hush.git

源码包含3个子目录：

* hush-lib：基础类库，支持应用框架
* hush-app：应用框架，可用于二次开发
* hush-pms：工作流框架，目前暂时不可用（待开发）

####2> 初始化应用框架

* **前提：**服务器上已经装有 Apache/Nginx 和 MySQL
* **如果需要**，可以在 hush/etc/global.config.php 中设置类库的安装路径
    * __COMM_LIB_DIR：第三方类库的安装目录（默认安装在 hush 同级目录 phplibs）
    * __HUSH_LIB_DIR：框架核心类库的安装目录（一般不需要配置）
* **如果需要**，可以在 hush/etc/database.mysql.php 中设置数据库的配置
    * __HUSH_DB_TYPE：数据库连接类型（默认 MYSQLI）
    * __HUSH_DB_HOST：数据库 IP 地址（默认 localhost）
    * __HUSH_DB_PORT：数据库端口地址（默认 3306）
    * __HUSH_DB_USER：数据库用户名（默认 root）
    * __HUSH_DB_PASS：数据库密码（默认 passwd）
* 进入 hush/hush-app/bin 目录（Linux 下需执行 chmod +x hush）
* 执行 ./hush sys init 按照提示安装类库、导入数据库、初始化目录等工作
    * 以上过程是完全自动化的，开发者根据提示操作即可
    * 看到 "Initialized successfully" 则表示安装成功

####3> 配置 Apache/Nginx 站点

Apache 站点配置如下（Windows）：

```
<VirtualHost *:80>
    ServerName hush-app-backend
	DocumentRoot "C:/workspace/hush/hush-app/web/backend"
	<Directory "C:/workspace/hush/hush-app/web/backend">
		AllowOverride None
		Order deny,allow
		Allow from all
		RewriteEngine on
		RewriteRule !\.(php|htm|js|ico|gif|jpg|png|css|swf|pdf|doc|xls|txt|ppt|zip|rar)$ index.php
	</Directory>
</VirtualHost>

<VirtualHost *:80>
	ServerName hush-app-frontend
	DocumentRoot "C:/workspace/hush/hush-app/web/frontend"
	<Directory "C:/workspace/hush/hush-app/web/frontend">
		AllowOverride All
		Order deny,allow
		Allow from all
	</Directory>
</VirtualHost>
```

Nginx 站点配置如下：

```
server {
    listen  80;
	server_name hush-app-backend;
	root /path/to/hush/hush-app/web/backend;
	location / {
		index  index.html index.htm index.php;
		if (!-e $request_filename) {
			rewrite  ^(.*)$  /index.php?$1  last;
			break;
		}
	}
	location ~ .*\.php$ {
		include fastcgi_params;
		fastcgi_param SCRIPT_FILENAME /path/to/hush/hush-app/web/backend$fastcgi_script_name;
		fastcgi_pass 127.0.0.1:9000;
		fastcgi_index index.php;
	}
}

server {
	listen  80;
	server_name hush-app-frontend;
	root /path/to/hush/hush-app/web/frontend;
	location / {
		index  index.html index.htm index.php;
		if (!-e $request_filename) {
			rewrite  ^(.*)$  /index.php?$1  last;
			break;
		}
	}
	location ~ .*\.php$ {
		include fastcgi_params;
		fastcgi_param SCRIPT_FILENAME /path/to/hush/hush-app/web/frontend$fastcgi_script_name;
		fastcgi_pass 127.0.0.1:9000;
		fastcgi_index index.php;
	}
}
```

####4> 修改本地 hosts 文件

为了便于调试，建议开发者修改本地的 hosts 文件来访问站点：

```
127.0.0.1 hush-app-frontend
127.0.0.1 hush-app-backend
```

重新启动 Apache/Nginx 服务器，就可以在浏览器中访问应用框架的界面了

##Quick Development Guide

####框架总体概览

README.txt 中是框架主要目录的概要说明，请参考。

####框架主要命令

* **注意：**执行 ./hush 即可看到所有命令
* hush sys init：系统初始化，建议仅在首次安装的时候使用
* hush sys uplib：更新依赖类库，当类库有更新的时候使用
* hush sys newapp：根据当前应用框架的代码生成新应用
* hush sys newdao：生成新的数据库模块类（Model）
* hush sys newctrl：生成新的控制器类（Controller）
* hush check all：检查所有运行目录的路径和权限
* hush clean all：清除模板缓存以及文件缓存
* hush doc build：生成 Hush 基础类库和应用框架的文档
* hush db backup [database]：备份指定数据库，[database] 是根据 database.mysql.php 文件中的 $_clusters 变量指定的，比如：default:0:master:0:ihush_apps 代表 $_clusters['default'][0]['master'][0] 的数据库配置，ihush_apps 代表数据库名
* hush db recover [database]：恢复制定数据库，[database] 的规则和 hush db backup [database] 命令相同

###开发简要说明

区别于其他框架，Hush Framework 鼓励大家抛开文档，直接通过阅读代码学习，其自带的应用框架（hush-app）中已经包含了绝大部分框架类库的用法，大家可以通过阅读源码或者亲自动手来加深对使用 Hush Framework 进行项目开发的认识。

Hush Framework 基于 Zend Framework，并在其基础上进行了重要的优化，同时还添加了许多实用的开发类库；开发者可以直接使用 hush doc build 生成 Hush Framework 的类库使用文档，不过某些类的用法还是需要参考 Zend Framework，比如 DAO 类中 SQL 的写法等。

以下是 Zend Framework 有关类库的使用文档，开发时可作参考：

* [Zend_Acl](http://framework.zend.com/manual/1.12/en/zend.acl.html)
* [Zend_Auth](http://framework.zend.com/manual/1.12/en/zend.auth.html)
* [Zend_Barcode](http://framework.zend.com/manual/1.12/en/zend.barcode.html)
* [Zend_Cache](http://framework.zend.com/manual/1.12/en/zend.cache.html)
* [Zend_Db](http://framework.zend.com/manual/1.12/en/zend.db.html)
* [Zend_Debug](http://framework.zend.com/manual/1.12/en/zend.debug.html)
* [Zend_Http](http://framework.zend.com/manual/1.12/en/zend.http.html)
* [Zend_Ldap](http://framework.zend.com/manual/1.12/en/zend.ldap.html)
* [Zend_Mail](http://framework.zend.com/manual/1.12/en/zend.mail.html)
* [Zend_Session](http://framework.zend.com/manual/1.12/en/zend.session.html)
* [Zend_Validate](http://framework.zend.com/manual/1.12/en/zend.validate.html)

以下是 Smarty 的使用文档，开发时可作参考：

* [Smarty_2](http://www.smarty.net/docsv2/en/)
* [Smarty_3](http://www.smarty.net/docs/en/)

####To be Added ...
