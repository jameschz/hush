{include file="frame/head.tpl"}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> 为流程 “{$flow.bpm_flow_name}” 定义流程步骤
</div>

<div class="mainbox">

{include file="frame/error.tpl"}

<form id="save_chart_form" method="post">
<input type="hidden" name="bpm_flow_id" value="{$smarty.get.flowId}" />
<div style="margin-bottom:10px;">
	<input id="add_node" type="button" value="添加节点" />&nbsp;
	<input id="clear_path" type="button" value="清除路径" />&nbsp;
	<input id="save_chart_as_xml" type="button" value="保存流程" />
</div>
</form>

<div class="flow_chart_box">
	<canvas id="canvasBox" width="800" height="600" style="border:1px solid black"></canvas>
	<div id="flowChartDragBox" class="flow_chart_drag_box">
		{foreach $nodeList as $node}
		<div id="drag{$node.bpm_node_id}" class="flow_chart_drag_flow flow_chart_node_type_{$node.bpm_node_type} flow_chart_node_attr_{$node.bpm_node_attr}" style="left:{$node.bpm_node_pos_left}px;top:{$node.bpm_node_pos_top}px">
			{$node.bpm_node_id} - {$node.bpm_node_name}
		</div>
		{/foreach}
	</div>
</div>

<script type="text/javascript" src="{$_root}js/flowchart.js"></script>
<script type="text/javascript">
$(document).ready(function(){
	FlowChart.initCanvas('canvasBox');
	{foreach $pathList as $path}FlowChart.drawLine('drag{$path.bpm_node_id_from}', 'drag{$path.bpm_node_id_to}');
	{/foreach}
	$('#flowChartDragBox').find('div').draggable({
		containment: '#flowChartDragBox',
		drag: function(){
			FlowChart.clearAll();
			{foreach $pathList as $path}FlowChart.drawLine('drag{$path.bpm_node_id_from}', 'drag{$path.bpm_node_id_to}');
{/foreach}
		},
		stop: function(){
			var nodeId = parseInt($(this).attr('id').substr(4), 10);
			var nodePosLeft = parseInt($(this).position().left, 10);
			var nodePosTop = parseInt($(this).position().top, 10);
			$.post('/bpm/adminNodeUpdatePos', {
				'nodeId' : nodeId,
				'nodePosLeft' : nodePosLeft,
				'nodePosTop' : nodePosTop
			}, function(data){
								
			});
		}
	}).click(function(){
		var nodeId = parseInt($(this).attr('id').substr(4), 10);
		$.overlay.frame('/bpm/adminNodeEdit/flowId/{$smarty.get.flowId}/nodeId/' + nodeId, function(){});
	});
});

$('#add_node').click(function(){
	$.overlay.frame('/bpm/adminNodeAdd/flowId/{$smarty.get.flowId}', function(){});
});
$('#clear_path').click(function(){
	$.get('/bpm/adminPathClear/flowId/{$smarty.get.flowId}', function(){
		location.reload();
	});
});
$('#save_chart_as_xml').click(function(){
	$('#save_chart_form').submit();
});
</script>

</div>

{include file="frame/foot.tpl"}