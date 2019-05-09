{if $charts}
	<div id="container" style="min-width: {$min-width}px; height: {$height}px; margin: 0 auto"></div>
	<script type="text/javascript">
	$(function () {
		$('#container').highcharts({$charts});
	});
	</script>
	<script src="{$_host_s}/app/chart/highcharts.js"></script>
	<script src="{$_host_s}/app/chart/modules/exporting.js"></script>
{else}
	<div class="qt_box_empty">
		<p>查无数据！</p>
	</div>
{/if}