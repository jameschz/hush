# Hush Framework 2.x

Powerful and Full-stack web application framework for PHP

Based on Zend Framework and Smarty template engine!

Version 2.0 release for more features!

## Snapshot

![snapshot](https://raw.githubusercontent.com/jameschz/hush/v2.0.0/snapshot.png)

## About Hush 1.x

See more INFO about the old version of Hush Framework in **Branch v1.1.1**

## New Features

1. 采用主流的 MVCS 框架结构，易于二次开发

2. 优化 ZendFramework & Smarty 组合（效率提升 N 倍）

3. Full Stack，完善的脚手架 & 后台框架（带界面），松耦合的前后台框架

4. 优化数据库连接，支持可编程的主从分离，分库分表策略

5. 后台自带强大的 RBAC 权限控制系统（可扩展）

6. 更好地支持分环境，多项目，适合中大型项目

## Quick Guide

#### 1> 安装源码

进入目录执行命令：

    git clone https://github.com/jameschz/hush.git

源码包含3个子目录：

* hush-lib：基础类库，支持应用框架
* hush-app：应用框架，可用于项目二次开发
* hush-run：运行时文件，包括临时文件、模板文件以及CDN文件等

默认环境分5个：

* Local：本地环境，对应配置 hush-app/etc/global.appcfg.php、database.mysql.php
* Dev：开发环境，对应配置 hush-app/etc/dev/*
* Test：测试环境，对应配置 hush-app/etc/dev/*
* Beta：Beta环境，对应配置 hush-app/etc/beta/*
* Release：正式环境，对应配置 hush-app/etc/release/*

#### 2> 安装系统

* 前提：服务器上已经装有 Apache Nginx MySQL
* 第一步：初始化框架代码
    * 进入应用框架 hush-app/bin 目录
    * 运行 hush sys uplib 安装基础类库，系统会自动安装
    * 类库一般会被安装到 hush/phplibs 目录下，此目录请在 gitignore 中排除
* 第二步：初始化数据库
    * 在开发机上准备好 MYSQL 数据库，并把 root 密码设置为 passwd（框架默认）
    * 执行 hush db init create 安装数据库
    * 执行 hush db table create 安装数据表
* 如果数据库配置，可以在 hush-lib/App/Db/ 目录下修改对应环境的数据库的配置（Local/Dev/Test/Beta/Release）
    * __HUSH_DB_TYPE：数据库连接类型（默认 Pdo_Mysql）
    * __HUSH_DB_HOST：数据库 IP 地址（默认 localhost）
    * __HUSH_DB_PORT：数据库端口地址（默认 3306）
    * __HUSH_DB_USER：数据库用户名（默认 root）
    * __HUSH_DB_PASS：数据库密码（默认 passwd）
* 第三步：初始化运行环境
    * 进入 hush-run/bin 目录，执行 build 命令，并给 run 目录赋权限（Linux 下需执行 chmod +x hush-run/run）
    * 进入 etc/ 目录，保存 env.php.sample 为 .env.php，里面配置的是当前环境的特殊变量（此文件一般给运维使用）

#### 3> 配置服务器（Apache / Nginx）

Apache 站点配置如下（Windows）

```
<VirtualHost *:80>
    ServerName hush-app
	DocumentRoot "C:/workspace/hush/hush-app/web/default"
	<Directory "C:/workspace/hush/hush-app/web/default">
		AllowOverride None
		Order deny,allow
		Allow from all
		RewriteEngine on
		RewriteRule !\.(php|htm|js|ico|gif|jpg|png|css|swf|pdf|doc|xls|txt|ppt|zip|rar)$ index.php
	</Directory>
</VirtualHost>
```

Nginx 站点配置如下

```
server {
    listen  80;
	server_name hush-app;
	root /path/to/hush/hush-app/web/default;
	location / {
		index  index.html index.htm index.php;
		if (!-e $request_filename) {
			rewrite  ^(.*)$  /index.php?$1  last;
			break;
		}
	}
	location ~ .*\.php$ {
		include fastcgi_params;
		fastcgi_param SCRIPT_FILENAME /path/to/hush/hush-app/web/default$fastcgi_script_name;
		fastcgi_pass 127.0.0.1:9000;
		fastcgi_index index.php;
	}
}
```

#### 4> 修改本地 hosts 文件

为了便于调试，建议开发者修改本地的 hosts 文件来访问站点：

```
127.0.0.1 hush-app
```

#### 5> 访问框架站点 

重新启动 Apache/Nginx 服务器，就可以在浏览器中访问应用框架的界面了。

本地站点地址：http://hush-app

默认登录账号和密码都是 sa

## Quick Development Guide

#### 如何开始

框架运行起来之后，就可以直接通过本地站点的后台 DEMO 案例学习框架的各项功能，DEMO列举如下：

DEMO菜单|功能
--|--
常用范例>后台范例>布局页面范例|后台Layout界面使用案例
常用范例>后台范例>列表页面范例|后台列表界面案例，包含数据增删查改功能
常用范例>后台范例>标签页面范例|后台列表界面案例，带TAB栏
常用范例>后台范例>页面切换范例|后台界面切换方式案例
常用范例>统计范例>折线图范例|统计范例：折线图
常用范例>统计范例>时线图范例|统计范例：时线图
常用范例>统计范例>柱状图范例|统计范例：柱状图
常用范例>统计范例>饼状图范例|统计范例：饼状图
常用范例>程序范例>数据库使用范例|数据库基础使用：单条创建，批量创建，事务成功/失败，事务嵌套（可分库）
常用范例>程序范例>缓存使用范例|缓存使用范例，默认使用 Redis
常用范例>程序范例>会话使用范例|缓存和会话使用，可以打通（即会话使用缓存存储）
常用范例>程序范例>日志使用范例|常用日志使用范例
测试工具>测试菜单>基础接口测试|接口测试工具，可以即时调试后台API

#### 命令行工具

* 注意：执行 ./hush 即可看到所有命令，以下是常用命令：
* hush sys uplib：更新依赖类库，当底层框架有更新的时候使用，一般只在首次安装时使用
* hush sys menu：初始化后台菜单，默认后台菜单的配置代码在 hush-lib/Core/Util/Menu.php 中
* hush db init [create|drop] {db_name}：增/删数据库
* hush db table [create|drop] {db_name}：增/删数据表
* hush db shard [create|drop] {db_name}：增/删分库分表
* hush db update {version_name} ：更新数据库版本
* hush sys newapp：根据当前应用框架的代码生成新应用
* hush sys newdao：生成新的数据库模块类（Model）
* hush sys newctrl：生成新的控制器类（Controller）
* hush doc build：生成基础类库和应用框架的帮助文档

#### 开发简要说明

Hush Framework 鼓励大家抛开文档，直接通过阅读代码学习，其自带的应用框架（hush-app）中已经包含了绝大部分框架类库的用法，大家可以通过阅读源码或者亲自动手来加深对使用 Hush Framework 进行项目开发的认识。

Hush Framework 基于 Zend Framework，并在其基础上进行了重要的优化，同时还添加了许多实用的开发类库；开发者可以直接使用 hush doc build 生成 Hush Framework 的类库使用文档，不过某些类的用法还是需要参考 Zend Framework，比如 DAO 类中 SQL 的写法等。

以下是 Zend Framework 有关类库的使用文档，开发时可作参考：

* [Zend_Framework](https://framework.zend.com/apidoc/1.12/index.html)
* [Smarty_2](http://www.smarty.net/docsv2/en/)
* [Smarty_3](http://www.smarty.net/docs/en/)

#### To be Added ...
