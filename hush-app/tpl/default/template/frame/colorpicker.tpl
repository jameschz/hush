<link href="{$_host_s}/app/color/css/colorpicker.css" rel="stylesheet" type="text/css" />
<script src="{$_host_s}/app/color/js/colorpicker.js" language="javascript" type="text/javascript"></script>
<script type="text/javascript">
$(function(){
	$(".colorbox").ColorPicker({
		onSubmit: function(hsb, hex, rgb, el) {
			$(el).val(hex);
			$(el).ColorPickerHide();
			$(el).css("background","#"+hex);
		},
		onBeforeShow: function () {
			$(this).ColorPickerSetColor(this.value);
		}
	});
});
</script>