<script type="text/javascript">
$(function(){
	var aw = {if $aw}{$aw}{else}0{/if};
	var ah = {if $ah}{$ah}{else}0{/if};
	$('.find_user').click(function(e){
		var finder = $(this);
		finder.attr('autocomplete', false);
		var o = $(this).offset();
		var w = $(this).width() + aw;
		var h = $(this).height() + ah;
		var l = o.left;
		var t = o.top + h + 1;
		if ($("#finder_box").length == 0) {
			$('<div id="finder_box" class="finder_box">'+
				'<div class="finder_box_search"><table border="0" cellspacing="0" cellpadding="0" style="width:100%"><tr><td style="width:75%"><input type="text" id="finder_input" placeholder="输入帐号名称" /></td><td class="finder_box_search_btn" id="finder_btn">搜索</td></tr></table></div>'+
				'<div class="finder_box_result" id="finder_result"></div>'+
				'</div>').appendTo("body");
			$('#finder_box').css({ 'visibility' : 'visible', 'width' : w, 'left' : l, 'top' : t });
			$('#finder_btn').click(function(e){
				$.post('/ajax/finduser', { 's' : $('#finder_input').val() }, function(data){
					var json = eval('('+data+')');
					$('#finder_result').html('');
					if (json.err == 0) {
						$.each(json.data, function(i, v) {
							$('#finder_result').append('<div class="finder_box_result_item"><input type="radio" name="finder_radio" value="'+v.id+'">&nbsp;帐号ID：'+v.id+'，帐号名称：'+v.name+'</div>')
						});
						$('input[name=finder_radio]').click(function(){
							finder.val($(this).val());
							setTimeout(function(){
								$('#finder_box').remove();
							}, 100);
							if(typeof find_channel_callback === "function") {
								find_channel_callback.apply(this, [$(this).val()]);
							}
						});
					}
				});
			});
			$("#finder_box").click(function(e){
				e.stopPropagation();
			});
		}
		$('#finder_btn').click();
		$(document).click(function(){
			$('#finder_box').remove(); 
		});
		e.stopPropagation();
	});

});
</script>