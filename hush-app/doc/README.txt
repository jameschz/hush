系统简介

* ZendFramework 和 Smarty 的完美结合（MVC）
* 优化的 ZendFramework Url Mapping 机制（比 ZF 快 N 倍）
* 完善的 Full Stack 前后台框架结构（带调试框架）
* 提供多数据库连接池，多数据库服务器负载均衡
* 强大的 ACL 权限控制系统（可扩展）
* 易安装，易配置，易扩展

1、关于 MVC：

实际上 HF 基本上复制了 ZF 的 MVC 结构，Module 使用的是基于 Zend_Db 的 Hush_Db 类，
Hush_Db 类使用的是 Zend_Db 基本的 Adaptor，然后在上面添加了一些优化的方法，比如多行插入等，
然后把 Hush_Debug 类嵌入其中，让用户可以很轻易的使用 Debug 控制台观测应用的所有 SQL。
而在 View 方面，HF 使用的是 Smarty 模板，这个理由就不多说了吧，然后优化了 ZF 的 URL Router 流程机制，
添加了包含模糊匹配功能的 mapping 文件，速度绝对快 （可以看到上图中的 Hush App Dispatch Time 
就是他的执行时间了，微秒级别的哦）。最后在 Controller 方面，HF 使用的 Hush_Page 类，
里面和 ZF 中的 Controller 基本没什么区别 Action 映射也是遵循 {ActionName}Action 规则，
要说不同就是添加了单独页面的可继承机制，简单说就是如果你不想使用 URL Router 机制，
你也可以方便的通过集成 Hush_Page 类来使用其提供的简便方法。

2、关于 ACL：

众所周知，权限控制是一个基于用户的应用系统的最核心部分，HF 的 ACL 模块 Hush_Acl 已经实现了基于 
Zend_Acl 的 RBAC 权限管理策略，而且极易扩展，因为 HF 的后台里面已经实现了菜单权限以及更细化的权限管理，
开发者只需要通过一些简单的界面操作就可以扩展 HF 的 ACL 权限控制到你的具体应用中。

3、关于 DEBUG：

开发过程中，免不了要调试和观测系统的运行状态，于是就出现了 Hush_Debug 模块，此模块可以说是 HF 的最大创新之一，
可以从上图看到黄色背景的部分就是 HF 的 Debug Console 了，用户可以通过 URL 中的 debug 参数 （例如 ?debug=time,sql） 
决定需要显示的 Debug 信息，红色的信息是系统自带的，目前支持页面时间调试和 SQL 调试，当然用户可以使用 Hush_Debug 
中提供的方法操作自己的 Debug 信息。目前 Hush_Debug 优先级包含 DEBUG、INFO、WARN、ERROR、FATAL 
五个级别的 Debug 信息，可以通过 setDebugLevel 方法来设置应用可显示的 Debug 级别，
另外用户还可以通过扩充 Hush_Debug_Writer 抽象类来实现自己的 Debug Log 记录接口。

4、关于 Full Stack：

Hush Framework 应用包括：框架类库和应用程序两个部分，而应用程序又包含：前台程序、后台程序两个部分，
所以可以看到 HF 的配制文件 （位于 etc/ 目录下） 分为三个大块：global.xxx.php(ini)、frontend.xxx.php(ini) 
和 backendxxx.php(ini) ，分别是全局、前台和后台的配制文件。安装的时候开发者把程序解压配制好配制文件中的 
Http Server 、 Database 以及 Cache 地址就可以运行 HF 应用程序了，后台默认超级用户/密码：sa/sa，
另外还可以通过 bin/ 目录下的 build.sh(bat) 来建立 HF 必须的一些 runtime 目录并赋权，使用相当方便，
关于其他的一些命令行工具比如创建模块工具等，日后可以慢慢完善。