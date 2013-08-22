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