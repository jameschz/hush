{if $aps.topmsg}
<div class="topmsg">{$aps.topmsg}</div>
{/if}

{if $charts}
	<div id="container" style="min-width: {$width}px; height: {$height}px; margin: 0 auto"></div>
	<script type="text/javascript">
	$(function () {
		Highcharts.setOptions({ 'global': { useUTC: false } });
		$('#container').highcharts({$charts});
	});
	</script>
	<script src="{$_root}app/chart/highcharts.js"></script>
	<script src="{$_root}app/chart/modules/exporting.js"></script>
{else}
	<div class="qt_box_empty">
		<p>查无数据！</p>
	</div>
{/if}