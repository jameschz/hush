<?php
/**
 * iHush Track
 *
 * @category   Track
 * @package    App_App_Default
 * @author     James.Huang <shagoo@gmail.com>
 * @license    http://www.apache.org/licenses/LICENSE-2.0
 * @version    $Id$
 */
 
require_once 'BasePage.php';

/**
 * @package App_App_Default
 */
class DemoPage extends BasePage
{	
	public function __init ()
	{
		parent::__init();
		
		$this->today = date('Y-m-d');
		$this->default_sday = date('Y-m-d', $this->dtime - 8 * 24 * 3600);
		$this->default_eday = date('Y-m-d', $this->dtime - 1 * 24 * 3600);
	}
	
	protected function _before_add ($data)
	{
	    // TODO：添加数据之前的回调处理（比如：字段格式检查）
	    switch ($this->bps->action) {
	        case 'list':
// 	            sleep(3);
// 	            $this->addErrorMsg('测试错误');
	            break;
	    }
	    return $data;
	}
	
	protected function _after_add ($data)
	{
	    // TODO：添加数据之后的回调处理（比如：关联数据更新）
	    return $data;
	}
	
	protected function _before_edit ($data)
	{
	    // TODO：编辑数据之前的回调处理（比如：字段格式检查）
	    return $data;
	}
	
	protected function _after_edit ($data)
	{
	    // TODO：编辑数据之后的回调处理（比如：关联数据更新）
	    return $data;
	}
	
	protected function _before_delete ($data)
	{
	    // TODO：删除数据之前的回调处理（比如：字段格式检查）
	    return $data;
	}
	
	protected function _after_delete ($data)
	{
	    // TODO：删除数据之后的回调处理（比如：关联数据更新）
	    return $data;
	}
	
	protected function _before_info ($data)
	{
	    // TODO：显示数据详情之前的回调处理（比如：字段数据填充）
	    return $data;
	}
	
	protected function _after_info ($data)
	{
	    // TODO：显示数据详情之前的回调处理（比如：字段数据填充）
	    return $data;
	}
	
	protected function _before_list ($search, $page_orders)
	{
	    // TODO：显示数据列表之前的回调处理（可以调整搜索条件和排序形式）
	    return array($search, $page_orders);
	}
	
	protected function _after_list ($data)
	{
	    // TODO：显示数据列表之后的回调处理（可以调整搜索结果列表）
	    return $data;
	}
	
	private function _init_test ()
	{
	    $this->bps->pkey = 'id';
	    $this->bps->model = 'Base_Test';
	    $this->bps->field = array(
	        'id' => array('type' => 'text', 'name' => 'ID', 'add' => false, 'edit' => false, 'list' => true),
	        'type' => array('type' => 'select', 'name' => '类别', 'add' => true, 'edit' => true, 'list' => true,
                'data' => array('类型1', '类型2', '类型3'),
	        ),
	        'title' => array('type' => 'text', 'name' => '标题', 'add' => true, 'edit' => true, 'list' => true),
	        'user_id' => array('type' => 'text', 'name' => '作者', 'add' => true, 'edit' => true, 'list' => true,
	            'find' => 'find_user',
	            'model' => array(
	                'list_only' => true, // 仅显示
	                'table' => 'Core_User',
	                'id_prim' => 'id',
	                'id_list' => 'name'
	            ),
	        ),
	        'ptime' => array('type' => 'date', 'name' => '发布日期', 'add' => true, 'edit' => true, 'list' => true),
	        'content' => array('type' => 'richtext', 'name' => '内容', 'add' => true, 'edit' => true, 'list' => true),
	        'images' => array('type' => 'file', 'name' => '图片', 'add' => true, 'edit' => true, 'list' => true,
	            'files' => array(
	                'pic1' => array('type' => 'imagecrop', 'name' => '宽截图1', 'size' => '400x300', 'value' => '', 'list' => true),
	                'pic2' => array('type' => 'imagecrop', 'name' => '长截图2', 'size' => '300x400', 'value' => '', 'list' => true),
	                'pic3' => array('type' => 'image', 'name' => '原始图3', 'size' => '400x400', 'value' => '', 'list' => true),
	            ),
	        ),
	        'files' => array('type' => 'file', 'name' => '附件', 'add' => true, 'edit' => true, 'list' => true,
	            'files' => array(
	                'audio1' => array('type' => 'audio', 'name' => '音乐', 'size' => 'mp3', 'value' => '', 'list' => true),
	                'video1' => array('type' => 'video', 'name' => '视频', 'size' => 'mp4', 'value' => '', 'list' => true),
	                'files1' => array('type' => 'files', 'name' => '文件', 'size' => '', 'value' => '', 'list' => true),
	            ),
	        ),
	    );
	}
	
	public function indexAction ()
	{
	    // TODO：默认页面
	}
	
	public function homeAction ()
	{
	    // TODO：默认页面
	}
	
	public function jumpAction ()
	{
	    require 'App/Util/Menu.php';
	    $menu = new App_Util_Menu();
	    $menu_res = $menu->getMenu('/demo/home');
	    $this->view->menu_res = $menu_res;
	}
	
	public function listAction () 
	{
	    $this->bps->action = 'list';
	    $this->bps->title = 'DEMO';
	    $this->bps->topmsg = '注意事项：在这里添加注意事项！';
	    
	    // 初始化关联模型
	    $this->_init_test();
	    
	    // 添加扩展菜单
	    $this->bps->extend = array(
	        array('name' => '预览', 'path' => 'preview'),
	    );
	    
	    // 添加搜索条件
	    $this->bps->filter = array(
	        'type' => array('type' => 'select', 'name' => '', 'order' => 0, 'default' => ''),
	        'title' => array('type' => 'text', 'name' => '标题', 'order' => 1, 'default' => ''),
	        'ptime' => array('type' => 'date', 'name' => '发布时间', 'order' => 2, 'default' => date('Y-m-d')),
	    );
	    
	    // 添加排序规则
	    $this->bps->orders = array(
	        'id' => array('order' => 'desc', 'icon' => true),
	        'title' => array('order' => 'desc'),
	    );
	    
	    $this->_crud($this->bps);
	}
	
	public function tabsAction ()
	{
	    $this->bps->action = 'tabs';
	    $this->bps->title = 'DEMO';
	    
	    // 设置Tabs信息
	    $this->bps->toptabs = array(
	        array('link' => "/demo/tabs?type=0", 'name' => 'TAB1', 'default' => 1),
	        array('link' => "/demo/tabs?type=1", 'name' => 'TAB2'),
	        array('link' => "/demo/tabs?type=2", 'name' => 'TAB3'),
	    );
	    
	    // 初始化关联模型
	    $this->_init_test();
	    
	    // 添加扩展菜单
	    $this->bps->extend = array(
	        array('name' => '预览', 'path' => 'preview'),
	    );
	    
	    // 添加搜索条件
	    $this->bps->filter = array(
	        'type' => array('type' => 'select', 'name' => '', 'order' => 0, 'default' => ''),
	        'title' => array('type' => 'text', 'name' => '标题', 'order' => 1, 'default' => ''),
	        'ptime' => array('type' => 'date', 'name' => '发布时间', 'order' => 2, 'default' => date('Y-m-d')),
	    );
	    
	    // 添加排序规则
	    $this->bps->orders = array(
	        'id' => array('order' => 'desc', 'icon' => true),
	        'title' => array('order' => 'desc'),
	    );
	    
	    // 无需分页则设置
	    $this->bps->page_num = 0;
	    
	    $this->_crud($this->bps);
	}
	
	public function stats1Action ()
	{
	    $this->bps->action = 'stat1';
	    $this->bps->title = 'DEMO1';
	    
        // 定义搜索条件
	    $this->bps->filter = array(
	        'sday' => array('type' => 'date', 'name' => '开始日期', 'order' => 0, 'cond' => 0, 'default' => $this->default_sday),
	        'eday' => array('type' => 'date', 'name' => '结束日期', 'order' => 1, 'cond' => 0, 'default' => $this->default_eday),
	        'name' => array('type' => 'text', 'name' => '搜索字段', 'order' => 2, 'cond' => 1, 'default' => ''),
	    );
	    
	    // 获取搜索条件值
	    $this->bps->topmsg = '注意事项：参数sday值为'.$this->param('sday').'；参数eday值为'.$this->param('eday');
	    
	    // 通过 Dao 的 getChartData 方法设值
// 	    $this->bps->model = 'Etl_EtlTableName';

	    // 通过直接赋值设值
	    $datas = array(
	        'ticks' => array('day1','day2','day3','day4'),
	        'datas' => array(
	            'num1' => array(12,3,5,35),
	            'num2' => array(3,15,9,26),
	        ),
	        'tables' => array(
	            array('day' => 'day1', 'num1' => 12, 'num2' => 3),
	            array('day' => 'day2', 'num1' => 3, 'num2' => 12),
	            array('day' => 'day3', 'num1' => 5, 'num2' => 9),
	            array('day' => 'day4', 'num1' => 35, 'num2' => 26),
	        ),
	        'totals' => array(
	            'num1' => 55,
	            'num2' => 50,
	        ),
	    );
	    
	    $this->bps->chart = array(
	        'type' => 'area',
	        'title' => '折线图案例',
	        'series' => array(
	            array('data' => 'num1', 'name' => '数据1'),
	            array('data' => 'num2', 'name' => '数据2'),
	        ),
	        'datas' => $datas,
	    );
	    
	    $this->bps->table = array(
	        'day' => array('name' => '日期'),
	        'num1' => array('name' => '数据1'),
	        'num2' => array('name' => '数据2'),
	    );

	    $this->_stat($this->bps);
	}
	
	public function stats2Action ()
	{
	    $this->bps->action = 'stat2';
	    $this->bps->title = 'DEMO2';
	    
	    // 定义搜索条件
	    $this->bps->filter = array(
	        'sday' => array('type' => 'date', 'name' => '开始日期', 'order' => 0, 'cond' => 0, 'default' => $this->default_sday),
	        'eday' => array('type' => 'date', 'name' => '结束日期', 'order' => 1, 'cond' => 0, 'default' => $this->default_eday),
	        'name' => array('type' => 'text', 'name' => '搜索字段', 'order' => 2, 'cond' => 1, 'default' => ''),
	    );
	    
	    // 通过直接赋值设值
	    $datas = array(
	        'datas' => array(
	            'num1' => array(
	                // 时间数据格式：[millisecond unixtime, value]...
	                array(1370131200000, 0.7695),array(1370217600000, 0.7648),array(1370304000000, 0.7645),array(1370390400000, 0.7638),array(1370476800000, 0.7549),array(1370563200000, 0.7562),array(1370736000000, 0.7574),array(1370822400000, 0.7543),array(1370908800000, 0.751),array(1370995200000, 0.7498),array(1371081600000, 0.7477),array(1371168000000, 0.7492),array(1371340800000, 0.7487),array(1371427200000, 0.748),array(1371513600000, 0.7466),array(1371600000000, 0.7521),array(1371686400000, 0.7564),array(1371772800000, 0.7621),array(1371945600000, 0.763),array(1372032000000, 0.7623),array(1372118400000, 0.7644),array(1372204800000, 0.7685),array(1372291200000, 0.7671),array(1372377600000, 0.7687),array(1372550400000, 0.7687),array(1372636800000, 0.7654),array(1372723200000, 0.7705),array(1372809600000, 0.7687),array(1372896000000, 0.7744),array(1372982400000, 0.7793),array(1373155200000, 0.7804),array(1373241600000, 0.777),array(1373328000000, 0.7824),array(1373414400000, 0.7705),array(1373500800000, 0.7635),array(1373587200000, 0.7652),array(1373760000000, 0.7656),array(1373846400000, 0.7655),array(1373932800000, 0.7598),array(1374019200000, 0.7619),array(1374105600000, 0.7628),array(1374192000000, 0.7609),array(1374364800000, 0.7599),array(1374451200000, 0.7584),array(1374537600000, 0.7562),array(1374624000000, 0.7575),array(1374710400000, 0.7531),array(1374796800000, 0.753),array(1374969600000, 0.7526),array(1375056000000, 0.754),array(1375142400000, 0.754),array(1375228800000, 0.7518),array(1375315200000, 0.7571),array(1375401600000, 0.7529),array(1375574400000, 0.7532),array(1375660800000, 0.7542),array(1375747200000, 0.7515),array(1375833600000, 0.7498),array(1375920000000, 0.7473),array(1376006400000, 0.7494),array(1376179200000, 0.7497),array(1376265600000, 0.7519),array(1376352000000, 0.754),array(1376438400000, 0.7543),array(1376524800000, 0.7492),array(1376611200000, 0.7502),array(1376784000000, 0.7503),array(1376870400000, 0.7499),array(1376956800000, 0.7453),array(1377043200000, 0.7487),array(1377129600000, 0.7487),array(1377216000000, 0.7472),array(1377388800000, 0.7471),array(1377475200000, 0.748),array(1377561600000, 0.7467),array(1377648000000, 0.7497),array(1377734400000, 0.7552),array(1377820800000, 0.7562),array(1377993600000, 0.7572),array(1378080000000, 0.7581),array(1378166400000, 0.7593),array(1378252800000, 0.7571),array(1378339200000, 0.7622),array(1378425600000, 0.7588),array(1378598400000, 0.7591),array(1378684800000, 0.7544),array(1378771200000, 0.7537),array(1378857600000, 0.7512),array(1378944000000, 0.7519),array(1379030400000, 0.7522),array(1379203200000, 0.7486),array(1379289600000, 0.75),array(1379376000000, 0.7486),array(1379462400000, 0.7396),array(1379548800000, 0.7391),array(1379635200000, 0.7394),array(1379808000000, 0.7389),array(1379894400000, 0.7411),array(1379980800000, 0.7422),array(1380067200000, 0.7393),array(1380153600000, 0.7413),array(1380240000000, 0.7396),array(1380412800000, 0.741),array(1380499200000, 0.7393),array(1380585600000, 0.7393),array(1380672000000, 0.7365),array(1380758400000, 0.7343),array(1380844800000, 0.7376),array(1381017600000, 0.737),array(1381104000000, 0.7362),array(1381190400000, 0.7368),array(1381276800000, 0.7393),array(1381363200000, 0.7397),array(1381449600000, 0.7385),array(1381622400000, 0.7377),array(1381708800000, 0.7374),array(1381795200000, 0.7395),array(1381881600000, 0.7389),array(1381968000000, 0.7312),array(1382054400000, 0.7307),array(1382227200000, 0.7309),array(1382313600000, 0.7308),array(1382400000000, 0.7256),array(1382486400000, 0.7258),array(1382572800000, 0.7247),array(1382659200000, 0.7244),array(1382832000000, 0.7244),array(1382918400000, 0.7255),array(1383004800000, 0.7275),array(1383091200000, 0.728),array(1383177600000, 0.7361),array(1383264000000, 0.7415),array(1383436800000, 0.7411),array(1383523200000, 0.7399),array(1383609600000, 0.7421),array(1383696000000, 0.74),array(1383782400000, 0.7452),array(1383868800000, 0.7479),array(1384041600000, 0.7492),array(1384128000000, 0.746),array(1384214400000, 0.7442),array(1384300800000, 0.7415),array(1384387200000, 0.7429),array(1384473600000, 0.741),array(1384646400000, 0.7417),array(1384732800000, 0.7405),array(1384819200000, 0.7386),array(1384905600000, 0.7441),array(1384992000000, 0.7418),array(1385078400000, 0.7376),array(1385251200000, 0.7379),array(1385337600000, 0.7399),array(1385424000000, 0.7369),array(1385510400000, 0.7365),array(1385596800000, 0.735),array(1385683200000, 0.7358),array(1385856000000, 0.7362),array(1385942400000, 0.7385),array(1386028800000, 0.7359),array(1386115200000, 0.7357),array(1386201600000, 0.7317),array(1386288000000, 0.7297),array(1386460800000, 0.7296),array(1386547200000, 0.7279),array(1386633600000, 0.7267),array(1386720000000, 0.7254),array(1386806400000, 0.727),array(1386892800000, 0.7276),array(1387065600000, 0.7278),array(1387152000000, 0.7267),array(1387238400000, 0.7263),array(1387324800000, 0.7307),array(1387411200000, 0.7319),array(1387497600000, 0.7315),array(1387670400000, 0.7311),array(1387756800000, 0.7301),array(1387843200000, 0.7308),array(1387929600000, 0.731),array(1388016000000, 0.7304),array(1388102400000, 0.7277),array(1388275200000, 0.7272),array(1388361600000, 0.7244),array(1388448000000, 0.7275),array(1388534400000, 0.7271),array(1388620800000, 0.7314),array(1388707200000, 0.7359),array(1388880000000, 0.7355),array(1388966400000, 0.7338),array(1389052800000, 0.7345),array(1389139200000, 0.7366),array(1389225600000, 0.7349),array(1389312000000, 0.7316),array(1389484800000, 0.7315),array(1389571200000, 0.7315),array(1389657600000, 0.731),array(1389744000000, 0.735),array(1389830400000, 0.7341),array(1389916800000, 0.7385),array(1390089600000, 0.7392),array(1390176000000, 0.7379),array(1390262400000, 0.7373),array(1390348800000, 0.7381),array(1390435200000, 0.7301),array(1390521600000, 0.7311),array(1390694400000, 0.7306),array(1390780800000, 0.7314),array(1390867200000, 0.7316),array(1390953600000, 0.7319),array(1391040000000, 0.7377),array(1391126400000, 0.7415),array(1391299200000, 0.7414),array(1391385600000, 0.7393),array(1391472000000, 0.7397),array(1391558400000, 0.7389),array(1391644800000, 0.7358),array(1391731200000, 0.7334),array(1391904000000, 0.7343),array(1391990400000, 0.7328),array(1392076800000, 0.7332),array(1392163200000, 0.7356),array(1392249600000, 0.7309),array(1392336000000, 0.7304),array(1392508800000, 0.73),array(1392595200000, 0.7295),array(1392681600000, 0.7268),array(1392768000000, 0.7281),array(1392854400000, 0.7289),array(1392940800000, 0.7278),array(1393113600000, 0.728),array(1393200000000, 0.728),array(1393286400000, 0.7275),array(1393372800000, 0.7306),array(1393459200000, 0.7295),array(1393545600000, 0.7245),array(1393718400000, 0.7259),array(1393804800000, 0.728),array(1393891200000, 0.7276),array(1393977600000, 0.7282),array(1394064000000, 0.7215),array(1394150400000, 0.7206),array(1394323200000, 0.7206),array(1394409600000, 0.7207),array(1394496000000, 0.7216),array(1394582400000, 0.7192),array(1394668800000, 0.721),array(1394755200000, 0.7187),array(1394928000000, 0.7188),array(1395014400000, 0.7183),array(1395100800000, 0.7177),array(1395187200000, 0.7229),array(1395273600000, 0.7258),array(1395360000000, 0.7249),array(1395532800000, 0.7247),array(1395619200000, 0.7226),array(1395705600000, 0.7232),array(1395792000000, 0.7255),array(1395878400000, 0.7278),array(1395964800000, 0.7271),array(1396137600000, 0.7272),array(1396224000000, 0.7261),array(1396310400000, 0.725),array(1396396800000, 0.7264),array(1396483200000, 0.7289),array(1396569600000, 0.7298),array(1396742400000, 0.7298),array(1396828800000, 0.7278),array(1396915200000, 0.7248),array(1397001600000, 0.7218),array(1397088000000, 0.72),array(1397174400000, 0.7202),array(1397347200000, 0.7222),array(1397433600000, 0.7236),array(1397520000000, 0.7239),array(1397606400000, 0.7238),array(1397692800000, 0.7238),array(1397779200000, 0.7238),array(1397952000000, 0.7239),array(1398038400000, 0.725),array(1398124800000, 0.7244),array(1398211200000, 0.7238),array(1398297600000, 0.7229),array(1398384000000, 0.7229),array(1398556800000, 0.7226),array(1398643200000, 0.722),array(1398729600000, 0.724),array(1398816000000, 0.7211),array(1398902400000, 0.721),array(1398988800000, 0.7209),array(1399161600000, 0.7209),array(1399248000000, 0.7207),array(1399334400000, 0.718),array(1399420800000, 0.7188),array(1399507200000, 0.7225),array(1399593600000, 0.7268),array(1399766400000, 0.7267),array(1399852800000, 0.7269),array(1399939200000, 0.7297),array(1400025600000, 0.7291),array(1400112000000, 0.7294),array(1400198400000, 0.7302),array(1400371200000, 0.7298),array(1400457600000, 0.7295),array(1400544000000, 0.7298),array(1400630400000, 0.7307),array(1400716800000, 0.7323),array(1400803200000, 0.7335),array(1400976000000, 0.7338),array(1401062400000, 0.7329),array(1401148800000, 0.7335),array(1401235200000, 0.7358),array(1401321600000, 0.7351),array(1401408000000, 0.7337),array(1401580800000, 0.7338),array(1401667200000, 0.7355),array(1401753600000, 0.7338),array(1401840000000, 0.7353),array(1401926400000, 0.7321),array(1402012800000, 0.733),array(1402185600000, 0.7327),array(1402272000000, 0.7356),array(1402358400000, 0.7381),array(1402444800000, 0.7389),array(1402531200000, 0.7379),array(1402617600000, 0.7384),array(1402790400000, 0.7388),array(1402876800000, 0.7367),array(1402963200000, 0.7382),array(1403049600000, 0.7356),array(1403136000000, 0.7349),array(1403222400000, 0.7353),array(1403395200000, 0.7357),array(1403481600000, 0.735),array(1403568000000, 0.735),array(1403654400000, 0.7337),array(1403740800000, 0.7347),array(1403827200000, 0.7327),array(1404000000000, 0.733),array(1404086400000, 0.7304),array(1404172800000, 0.731),array(1404259200000, 0.732),array(1404345600000, 0.7347),array(1404432000000, 0.7356),array(1404604800000, 0.736),array(1404691200000, 0.735),array(1404777600000, 0.7346),array(1404864000000, 0.7329),array(1404950400000, 0.7348),array(1405036800000, 0.7349),array(1405209600000, 0.7352),array(1405296000000, 0.7342),array(1405382400000, 0.7369),array(1405468800000, 0.7393),array(1405555200000, 0.7392),array(1405641600000, 0.7394),array(1405814400000, 0.739),array(1405900800000, 0.7395),array(1405987200000, 0.7427),array(1406073600000, 0.7427),array(1406160000000, 0.7428),array(1406246400000, 0.7446),array(1406419200000, 0.7447),array(1406505600000, 0.744),array(1406592000000, 0.7458),array(1406678400000, 0.7464),array(1406764800000, 0.7469),array(1406851200000, 0.7446),array(1407024000000, 0.7447),array(1407110400000, 0.745),array(1407196800000, 0.7477),array(1407283200000, 0.7472),array(1407369600000, 0.7483),array(1407456000000, 0.7457),array(1407628800000, 0.746),array(1407715200000, 0.747),array(1407801600000, 0.748),array(1407888000000, 0.7482),array(1407974400000, 0.7482),array(1408060800000, 0.7463),array(1408233600000, 0.7469),array(1408320000000, 0.7483),array(1408406400000, 0.7508),array(1408492800000, 0.7541),array(1408579200000, 0.7529),array(1408665600000, 0.7551),array(1408838400000, 0.7577),array(1408924800000, 0.758),array(1409011200000, 0.7593),array(1409097600000, 0.758),array(1409184000000, 0.7585),array(1409270400000, 0.7614),array(1409443200000, 0.7618),array(1409529600000, 0.7618),array(1409616000000, 0.7614),array(1409702400000, 0.7604),array(1409788800000, 0.7725),array(1409875200000, 0.7722),array(1410048000000, 0.7721),array(1410134400000, 0.7753),array(1410220800000, 0.773),array(1410307200000, 0.7742),array(1410393600000, 0.7736),array(1410480000000, 0.7713),array(1410652800000, 0.7717),array(1410739200000, 0.7727),array(1410825600000, 0.7716),array(1410912000000, 0.7772),array(1410998400000, 0.7739),array(1411084800000, 0.7794),array(1411257600000, 0.7788),array(1411344000000, 0.7782),array(1411430400000, 0.7784),array(1411516800000, 0.7824),array(1411603200000, 0.7843),array(1411689600000, 0.7884),array(1411862400000, 0.7891),array(1411948800000, 0.7883),array(1412035200000, 0.7916),array(1412121600000, 0.7922),array(1412208000000, 0.7893),array(1412294400000, 0.7989),array(1412467200000, 0.7992),array(1412553600000, 0.7903),array(1412640000000, 0.7893),array(1412726400000, 0.7853),array(1412812800000, 0.788),array(1412899200000, 0.7919),array(1413072000000, 0.7912),array(1413158400000, 0.7842),array(1413244800000, 0.79),array(1413331200000, 0.779),array(1413417600000, 0.7806),array(1413504000000, 0.7835),array(1413676800000, 0.7844),array(1413763200000, 0.7813),array(1413849600000, 0.7864),array(1413936000000, 0.7905),array(1414022400000, 0.7907),array(1414108800000, 0.7893),array(1414281600000, 0.7889),array(1414368000000, 0.7875),array(1414454400000, 0.7853),array(1414540800000, 0.7916),array(1414627200000, 0.7929),array(1414713600000, 0.7984),array(1414886400000, 0.7999),array(1414972800000, 0.8012),array(1415059200000, 0.7971),array(1415145600000, 0.8009),array(1415232000000, 0.8081),array(1415318400000, 0.803),array(1415491200000, 0.8025),array(1415577600000, 0.8051),array(1415664000000, 0.8016),array(1415750400000, 0.804),array(1415836800000, 0.8015),array(1415923200000, 0.7985),array(1416096000000, 0.7988),array(1416182400000, 0.8032),array(1416268800000, 0.7976),array(1416355200000, 0.7965),array(1416441600000, 0.7975),array(1416528000000, 0.8071),array(1416700800000, 0.8082),array(1416787200000, 0.8037),array(1416873600000, 0.8016),array(1416960000000, 0.7996),array(1417046400000, 0.8022),array(1417132800000, 0.8031),array(1417305600000, 0.804),array(1417392000000, 0.802),array(1417478400000, 0.8075),array(1417564800000, 0.8123),array(1417651200000, 0.8078),array(1417737600000, 0.8139),array(1417910400000, 0.8135),array(1417996800000, 0.8119),array(1418083200000, 0.8081),array(1418169600000, 0.8034),array(1418256000000, 0.8057),array(1418342400000, 0.8024),array(1418515200000, 0.8024),array(1418601600000, 0.804),array(1418688000000, 0.7993),array(1418774400000, 0.8102),array(1418860800000, 0.8139),array(1418947200000, 0.8177),array(1419120000000, 0.818),array(1419206400000, 0.8176),array(1419292800000, 0.8215),array(1419379200000, 0.82),array(1419465600000, 0.8182),array(1419552000000, 0.8213),array(1419724800000, 0.8218),array(1419811200000, 0.8229),array(1419897600000, 0.8225),array(1419984000000, 0.8266),array(1420070400000, 0.8262),array(1420156800000, 0.8331),array(1420329600000, 0.8371),array(1420416000000, 0.838),array(1420502400000, 0.8411),array(1420588800000, 0.8447),array(1420675200000, 0.848),array(1420761600000, 0.8445),array(1420934400000, 0.8425),array(1421020800000, 0.8451),array(1421107200000, 0.8495),array(1421193600000, 0.8482),array(1421280000000, 0.8598),array(1421366400000, 0.8643),array(1421539200000, 0.8648),array(1421625600000, 0.8617),array(1421712000000, 0.8658),array(1421798400000, 0.8613),array(1421884800000, 0.8798),array(1421971200000, 0.8922),array(1422144000000, 0.899),array(1422230400000, 0.8898),array(1422316800000, 0.8787),array(1422403200000, 0.8859),array(1422489600000, 0.8834),array(1422576000000, 0.8859),array(1422748800000, 0.8843),array(1422835200000, 0.8817),array(1422921600000, 0.871),array(1423008000000, 0.8813),array(1423094400000, 0.8713),array(1423180800000, 0.8837),array(1423353600000, 0.8839),array(1423440000000, 0.8831),array(1423526400000, 0.8833),array(1423612800000, 0.8823),array(1423699200000, 0.877),array(1423785600000, 0.8783),array(1423958400000, 0.8774),array(1424044800000, 0.8807),array(1424131200000, 0.8762),array(1424217600000, 0.8774),array(1424304000000, 0.8798),array(1424390400000, 0.8787),array(1424563200000, 0.8787),array(1424649600000, 0.8824),array(1424736000000, 0.8818),array(1424822400000, 0.8801),array(1424908800000, 0.8931),array(1424995200000, 0.8932),array(1425168000000, 0.896),array(1425254400000, 0.8941),array(1425340800000, 0.8948),array(1425427200000, 0.9026),array(1425513600000, 0.9066),array(1425600000000, 0.9222),array(1425772800000, 0.9221),array(1425859200000, 0.9214),array(1425945600000, 0.9347),array(1426032000000, 0.9482),array(1426118400000, 0.9403),array(1426204800000, 0.9528),array(1426377600000, 0.9541),array(1426464000000, 0.9462),array(1426550400000, 0.9435),array(1426636800000, 0.9203),array(1426723200000, 0.9381),array(1426809600000, 0.9241),array(1426982400000, 0.9237),array(1427068800000, 0.9135),array(1427155200000, 0.9152),array(1427241600000, 0.9114),array(1427328000000, 0.9188),array(1427414400000, 0.9184),array(1427587200000, 0.9188),array(1427673600000, 0.9231),array(1427760000000, 0.9319),array(1427846400000, 0.9291),array(1427932800000, 0.9188),array(1428019200000, 0.9109),array(1428192000000, 0.9091),array(1428278400000, 0.9154),array(1428364800000, 0.9246),array(1428451200000, 0.9276),array(1428537600000, 0.9382),array(1428624000000, 0.9431),array(1428796800000, 0.9426),array(1428883200000, 0.9463),array(1428969600000, 0.9386),array(1429056000000, 0.9357),array(1429142400000, 0.9293),array(1429228800000, 0.9254),array(1429401600000, 0.9251),array(1429488000000, 0.9312),array(1429574400000, 0.9315),array(1429660800000, 0.9323),array(1429747200000, 0.9236),array(1429833600000, 0.9196),array(1430006400000, 0.9201),array(1430092800000, 0.9184),array(1430179200000, 0.9106),array(1430265600000, 0.8983),array(1430352000000, 0.8909),array(1430438400000, 0.8928),array(1430611200000, 0.8941),array(1430697600000, 0.8972),array(1430784000000, 0.894),array(1430870400000, 0.8808),array(1430956800000, 0.8876),array(1431043200000, 0.8925),array(1431216000000, 0.8934),array(1431302400000, 0.8964),array(1431388800000, 0.8917),array(1431475200000, 0.8805),array(1431561600000, 0.8764),array(1431648000000, 0.8732),array(1431820800000, 0.8737),array(1431907200000, 0.8838),array(1431993600000, 0.8969),array(1432080000000, 0.9014),array(1432166400000, 0.8999),array(1432252800000, 0.9076),array(1432425600000, 0.9098),array(1432512000000, 0.911),array(1432598400000, 0.9196),array(1432684800000, 0.917),array(1432771200000, 0.9133),array(1432857600000, 0.9101),array(1433030400000, 0.9126),array(1433116800000, 0.9151),array(1433203200000, 0.8965),array(1433289600000, 0.8871),array(1433376000000, 0.8898),array(1433462400000, 0.8999),array(1433635200000, 0.9004),array(1433721600000, 0.8857),array(1433808000000, 0.8862),array(1433894400000, 0.8829),array(1433980800000, 0.8882),array(1434067200000, 0.8873),array(1434240000000, 0.8913),array(1434326400000, 0.8862),array(1434412800000, 0.8891),array(1434499200000, 0.8821),array(1434585600000, 0.8802),array(1434672000000, 0.8808),array(1434844800000, 0.8794),array(1434931200000, 0.8818),array(1435017600000, 0.8952),array(1435104000000, 0.8924),array(1435190400000, 0.8925),array(1435276800000, 0.8955),array(1435449600000, 0.9113),array(1435536000000, 0.89),array(1435622400000, 0.895)
	            ),
	        ),
	    );
	    
	    $this->bps->chart = array(
	        'type' => 'time',
	        'title' => '时线图案例',
	        'series' => array(
	            array('data' => 'num1', 'name' => '数据1'),
	        ),
	        'datas' => $datas,
	    );

	    $this->_stat($this->bps);
	}
	
	public function stats3Action ()
	{
	    $this->bps->action = 'stat3';
	    $this->bps->title = 'DEMO3';
	    
	    // 定义搜索条件
	    $this->bps->filter = array(
	        'sday' => array('type' => 'date', 'name' => '开始日期', 'order' => 0, 'cond' => 0, 'default' => $this->default_sday),
	        'eday' => array('type' => 'date', 'name' => '结束日期', 'order' => 1, 'cond' => 0, 'default' => $this->default_eday),
	        'name' => array('type' => 'text', 'name' => '搜索字段', 'order' => 2, 'cond' => 1, 'default' => ''),
	    );
	    
	    // 通过直接赋值设值
	    $datas = array(
	        'ticks' => array('month1','month2','month3','month4'),
	        'datas' => array(
	            'num1' => array(12,3,5,35),
	            'num2' => array(3,15,9,26),
	            'num3' => array(12,10,18,36),
	        ),
	    );
	    
	    $this->bps->chart = array(
	        'type' => 'bar',
	        'title' => '柱状图案例',
	        'series' => array(
	            array('data' => 'num1', 'name' => '数据1'),
	            array('data' => 'num2', 'name' => '数据2'),
	            array('data' => 'num3', 'name' => '数据3'),
	        ),
	        'datas' => $datas,
	    );
	    
	    $this->_stat($this->bps);
	}
	
	public function stats4Action ()
	{
	    $this->bps->action = 'stat4';
	    $this->bps->title = 'DEMO4';
	    
	    // 定义搜索条件
	    $this->bps->filter = array(
	        'sday' => array('type' => 'date', 'name' => '开始日期', 'order' => 0, 'cond' => 0, 'default' => $this->default_sday),
	        'eday' => array('type' => 'date', 'name' => '结束日期', 'order' => 1, 'cond' => 0, 'default' => $this->default_eday),
	        'name' => array('type' => 'text', 'name' => '搜索字段', 'order' => 2, 'cond' => 1, 'default' => ''),
	    );
	    
	    // 通过直接赋值设值
	    $datas = array(
	        'ticks' => array('month1','month2','month3','month4'),
	        'datas' => array(
	            'num1' => array(
    	            array('name' => 'Vivo', 'y' => 1273),
    	            array('name' => 'Xiaomi', 'y' => 1346),
    	            array('name' => 'Huawei', 'y' => 2733),
    	            array('name' => 'Oppo', 'y' => 1442),
	                array('name' => '其他', 'y' => 363),
	            ),
	        ),
	    );
	    
	    $this->bps->chart = array(
	        'type' => 'pie',
	        'title' => '饼状图案例',
	        'series' => array(
	            array('data' => 'num1', 'name' => '数据1'),
	        ),
	        'datas' => $datas,
	    );
	    
	    $this->_stat($this->bps);
	}
	
	public function previewAction ()
	{
	    $id = $this->param('id');
	    
	    // 跳转到二维码生成逻辑中去
	    $this->forward('/ajax/qrcode?s=id:'.$id);
	}
	
	public function code1Action ()
	{
	    $act = $this->param('act');
	    
	    // 初始化DAO
	    $dao = $this->dao->load('Base_Test');
	    
	    // 是否调试SQL，打开可以在日志里面查看SQL
	    $dao->debug(true);
	    
	    // 单条插入
	    if ($act == '1') {
	        $num = $dao->create(array(
	            'type' => 0,
	            'title' => 'create 1',
	            'dtime' => $this->dtime,
	        ));
	        $msgs[] = '单条插入成功';
	        $this->view->msgs = $msgs;
	    }
	    
	    // 批量插入
	    if ($act == '2') {
	        $num = $dao->batchCreate(array('type', 'title', 'dtime'),array(
	            array(0, 'batch create 1', $this->dtime),
	            array(1, 'batch create 2', $this->dtime),
	            array(2, 'batch create 3', $this->dtime),
	        ));
	        $msgs[] = '批量插入成功';
	        $this->view->msgs = $msgs;
	    }
	    
	    // 测试事务
	    if ($act == '3') {
	        try {
	            $dao->testTrans1(1);
                $msgs[] = '事务执行成功';
	        } catch (Exception $e) {
	            $msgs[] = '事务执行失败：' . $e->getMessage();
	        }
	        $this->view->msgs = $msgs;
	    }
	    
	    // 测试事务
	    if ($act == '4') {
	        try {
	            $dao->testTrans1(-1);
	            $msgs[] = '事务执行成功';
	        } catch (Exception $e) {
	            $msgs[] = '事务执行失败：' . $e->getMessage();
	        }
	        $this->view->msgs = $msgs;
	    }
	    
	    // 测试事务嵌套
	    if ($act == '5') {
	        try {
	            $dao->testTrans2(1);
	            $msgs[] = '事务执行成功';
	        } catch (Exception $e) {
	            $msgs[] = '事务执行失败：' . $e->getMessage();
	        }
	        $this->view->msgs = $msgs;
	    }
	    
	    // 获取数据
	    $dao = $this->dao->load('Base_Test');
	    $res = $dao->search(array(), array(), array('dtime desc'), 20);
	    if ($res) {
	        $this->view->result = $res;
	    }
	}
	
	public function code2Action ()
	{
	    // 获取缓存值
	    require_once 'App/Cache/Demo.php';
	    $cache = new App_Cache_Demo('cache_test');
	    $res = $cache->get();
	    if ($res) {
	        $this->view->result = json_encode($res, JSON_PRETTY_PRINT);
	    }
	}
	
	public function code3Action ()
	{
	    $act = $this->param('act');
	    
	    // 设置会话
	    if ($act == '1') {
	        $this->session('time', $this->dtime);
	        $this->forward('/demo/code3');
	    }
	    
	    // 获取缓存ID
	    $token_id = session_id();
	    $this->view->token_id = $token_id;
	    
	    // 获取缓存值
	    require_once 'Core/Cache/Token.php';
	    $cache = new Core_Cache_Token($token_id);
	    $res = $cache->get();
	    if ($res) {
	        $this->view->result = json_encode($res, JSON_PRETTY_PRINT);
	    }
	    
	    // 获取会话值
	    $res = $this->session('time');
	    if ($res) {
	        $this->view->session_time = $res;
	    }
	}
	
	public function code4Action ()
	{
	    $act = $this->param('act');
	    
	    // 设置日志
	    if ($act == '1') {
	        Core_Util::core_log('log at '.date('Y-m-d H:i:s'));
	        $this->forward('/demo/code4');
	    }

	    // 获取日志
	    $log_path = __LOG_DIR."/core.log";
	    $lines = Core_Util::tail($log_path, 10);
	    $this->view->result = $lines;
	}
}