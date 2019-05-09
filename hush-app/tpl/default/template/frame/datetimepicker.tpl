<link href="{$_host_s}/app/datetimepicker/jquery.datetimepicker.css" rel="stylesheet" type="text/css" />
<script src="{$_host_s}/app/datetimepicker/jquery.datetimepicker.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$(".datetimepicker").datetimepicker({
		format:'Y-m-d H:i:00',
		step:10
	});
	$(".datepicker").datetimepicker({
		timepicker:false,
		format:'Y-m-d',
	});
});
</script>