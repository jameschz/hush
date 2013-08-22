#Hush Framework

---

###Summary

> Powerful and Full-stack web application framework for PHP

> Based on Zend Framework and Smarty template engine!

> By -- James.Huang (黄隽实) 

###Features

1. ZendFramework 和 Smarty 的完美结合（MVC/S）

2. 优化的 ZendFramework url routing（效率提升 N 倍）

3. 完善的 Full Stack 前后台松耦合框架结构（带调试框架）

4. 优化数据库连接，支持主从分离，分库分表策略

5. 强大的 RBAC 权限控制系统（可扩展）

6. 整合 BPM 流程管理框架（可编程）

7. 多进程，消息处理（用于 CLI） 

###Quick Guide

#####1> 安装框架源码

进入目录执行命令：

    git clone https://github.com/jameschz/hush.git

源码包含3个子目录：

* hush-lib：基础类库，支持应用框架
* hush-app：应用框架，可用于二次开发
* hush-pms：工作流框架，目前暂时不可用（待开发）

#####2> 初始化应用框架

* **前提：**服务器上已经装有 Apache/Nginx 和 MySQL
* 进入 hush/hush-app/bin 目录（Linux 下需执行 chmod +x hush）
* **如果需要**，可以在 hush/etc/global.config.php 中设置类库的安装路径
    * __COMM_LIB_DIR：第三方类库的安装目录（默认安装在 hush 同级目录 phplibs）
    * __HUSH_LIB_DIR：框架核心类库的安装目录（一般不需要配置）
* **如果需要**，可以在 hush/etc/database.mysql.php 中设置数据库的配置
    * type：数据库连接类型（默认 MYSQLI）
    * host：数据库 IP 地址（默认 localhost）
    * port：数据库端口地址（默认 3306）
    * user：数据库用户名（默认 root）
    * pass：数据库密码（默认 passwd）
* 执行 ./hush sys init 按照提示安装类库、导入数据库、初始化目录等工作
    * 以上过程是完全自动化的，开发者根据提示操作即可
    * 看到 "Initialized successfully" 则表示安装成功

#####3> 配置 Apache/Nginx 站点

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

#####4> 修改本地 hosts 文件

为了便于调试，建议开发者修改本地的 hosts 文件来访问站点：

```
127.0.0.1 hush-app-frontend
127.0.0.1 hush-app-backend
```

重新启动 Apache/Nginx 服务器，就可以在浏览器中访问应用框架的界面了

###Quick Develop Guide

以下是框架的目录说明：

hush-framework

|

|- hush-app                 HF Demo 应用程序

|  |- bin                   可执行文件目录

|  |- dat                   临时存储文件

|  |- doc                   主要文档目录

|  |- etc                   配置文件目录

|  |- lib                   主要逻辑目录

|  |  |- Ihush
|  |     |- Acl             ACL 权限逻辑类库

|  |     |- App

|  |     |  |- Backend

|  |     |  |  |- Page      后台 Controller 逻辑

|  |     |  |  |- Remote    后台 Service 逻辑

|  |     |  |- Frontend

|  |     |     |- Page      前台 Controller 逻辑

|  |     |- Bpm             Bpm 逻辑类库

|  |     |- Dao

|  |        |- Apps         Apps 库的 Module/Dao 类库

|  |        |- Core         Core 库的 Module/Dao 类库

|  |- tpl

|  |  |- backend            后台模板文件

|  |  |- frontend           前台模板文件

|  |- web

|     |- backend            后台 DocumentRoot（站点目录）

|     |- frontend           前台 DocumentRoot（站点目录）

|

|- hush-lib

|  |- Hush

|     |- Acl                Acl 权限类库

|     |- App                App Url Dispatcher

|     |- Auth				

|     |- Bpm                Bpm 类库

|     |- Cache              Cache 类库

|     |- Chart              图像类库

|     |- Crypt              加密类（Rsa）

|     |- Date

|     |- Db                 数据库层（Module）类库

|     |- Debug              调试类库

|     |- Document           文档类库

|     |- Examples           一些例子（主要针对 Cli 程序）

|     |- Html               Html 构建类库

|     |- Http               远程访问类库

|     |- Json				

|     |- Mail               邮件收发类库

|     |- Message            消息类库

|     |- Mongo              Mongodb 类库

|     |- Page               页面层（Controller）类库

|     |- Process            多进程类库

|     |- Service            服务层（Service）类库

|     |- Session			

|     |- Socket             Socket 类库

|     |- Util               工具类库

|     |- View               展示层（View）类库

|

|- hush-pms                 PHP Message Server
