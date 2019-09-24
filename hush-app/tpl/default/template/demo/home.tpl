{include file="frame/layout/head.tpl"}

<script src="{$_root}app/chart/highcharts.js"></script>
<script src="{$_root}app/chart/modules/exporting.js"></script>

<ul class="fz-nav"><!--nav模板-->
  <li class="check">产品A</li>
  <li>产品B</li>
  <li>产品C</li>
</ul>

<div class="row"><!--最外层行-->
  <div class="fz-layout-2"><!--布局模块分配比例-->
    <div class="fz-card"><!--代码写在这个卡片内部-->
      <div class="fz-head"><!--卡片头部-->
        <p class="fz-title">日新增</p> <!--卡片头部标题-->
        <p class="fz-date">09/07</p><!--卡片头部标签-->
      </div>
      <div class="fz-body"><!--卡片内容 以下内容都自定义-->
        <p class="fz-number">1000</p><!--固定模板，不用可删除-->
        <div class="fz-number-bt"><!--固定模板，不用可删除-->
          <p>趋势上升</p>
          <p>90% ↑</p>
        </div>
      </div>
    </div>
  </div>
  <div class="fz-layout-2">
    <div class="fz-card">
      <div class="fz-head">
        <p class="fz-title">日登录</p>
        <p class="fz-date">09/07</p>
      </div>
      <div class="fz-body">
        <p class="fz-number">10000</p>
        <div class="fz-number-bt">
          <p>趋势下降</p>
          <p>20% ↓</p>
        </div>
      </div>
    </div>
  </div>
  <div class="fz-layout-4">
    <div class="fz-card">
      <div class="fz-head">
        <p class="fz-title">收入数据</p>
        <p class="fz-date">09/07</p>
      </div>
      <div class="fz-body">
        <div class="fz-mb">
          <p class="fz-number">261253</p>
          <div class="fz-number-bt">
            <p class="cgreen">新增收入</p>
          </div>
        </div>
        <div class="fz-mb">
          <p class="fz-number">1623</p>
          <div class="fz-number-bt">
            <p class="cgreen">新增订单</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="fz-layout-4">
    <div class="fz-card">
      <div class="fz-head">
        <p class="fz-title">收入曲线</p>
        <p class="fz-date">09/01 ~ 09/07</p>
      </div>
      <div class="fz-body" style="padding:5px;">
        <img src="/img/test1.png" style="height:70px;width:100%;">
      </div>
    </div>
  </div>
</div>

<div class="row">
  <div class="fz-layout-8">
    <div class="row-x"><!--内嵌布局-->
      <div class="fz-layout-12">
        <div class="fz-card">
          <div class="fz-head">
            <p class="fz-title">折线图（引用）</p>
          </div>
          <div class="fz-body">
            <div id="container" style="width:100%;height:300px;margin:0 auto;"></div>
            <script type="text/javascript">
            $(function(){
            	Highcharts.setOptions({ 'global': { useUTC: false } });
            	$.get('/demo/stats1?_get_json=1', function(data){
            		$('#container').highcharts(eval('('+data+')'));
            	});
            });
            </script>
          </div>
        </div>
      </div>
      <div class="fz-layout-12">
        <div class="row-x">
          <div class="fz-layout-6">
            <div class="fz-card">
              <div class="fz-head">
                <p class="fz-title">饼状图</p>
              </div>
              <div class="fz-body">
                <img src="/img/test2.png" style="width:100%;">
              </div>
            </div>
          </div>
          <div class="fz-layout-6">
            <div class="fz-card">
              <div class="fz-head">
                <p class="fz-title">柱状图</p>
              </div>
              <div class="fz-body">
                <img src="/img/test3.png" style="width:100%;">
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="fz-layout-4">
    <div class="row-x">
    <div class="fz-layout-12">
      <div class="fz-card">
        <div class="fz-head">
          <p class="fz-title">版本信息</p>
        </div>
        <div class="fz-body">
          <table class="fz-table"><!-- 表格样式-->
            <tr>
              <td>版本号</td>
              <td>1.0</td>
            </tr>
            <tr>
              <td>作者</td>
              <td>张三</td>
            </tr>
            <tr>
              <td>邮箱</td>
              <td>xx@qq.com</td>
            </tr>
            <tr>
              <td>交流群</td>
              <td>121212212</td>
            </tr>
          </table>
        </div>
      </div>
    </div>
      <div class="fz-layout-12">
        <div class="fz-card">
          <div class="fz-head">
            <p class="fz-title">版本信息</p>
          </div>
          <div class="fz-body">
            <div class="fz-txt">
            <p>1、版本信息1</p>
            <p>2、版本信息2</p>
            <p>3、版本信息3</p>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

{include file="frame/layout/foot.tpl"}