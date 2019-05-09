{if !$upload_logic_setted}
<iframe id="upload_form_target" name="upload_form_target" width="0" height="0" style="visibility:hidden"></iframe>
<input type="hidden" id="upload_form_file" name="upload_form_file" value="" />
<input type="hidden" id="upload_form_img" name="upload_form_img" value="" />
<input type="hidden" id="upload_form_src" name="upload_form_src" value="" />
<script type="text/javascript">
$(function(){
	$('.upload_item').change(function(){
		$('#upload_form_file').val($(this).attr('name'));
		$('#upload_form_img').val($(this).attr('img'));
		$('#upload_form_src').val($(this).attr('src'));
		var old_action = $('#{$upload_form_id}').attr('action');
		$('#{$upload_form_id}').attr('action', '{$_host_u}/hush/upload/from/upload');
		$('#{$upload_form_id}').attr('target', 'upload_form_target');
		$('#{$upload_form_id}').submit();
		$('#{$upload_form_id}').attr('action', old_action);
		$('#{$upload_form_id}').attr('target', '');
	});
});
</script>
{/if}
{assign var="upload_logic_setted" value="1"}
<div class="img_box" style="margin:0px 10px 0px 0px;">
	<div class="img_box_in">
		<input type="file" class="upload_item" id="up_file_{$upload_item_id}" img="up_img_{$upload_item_id}" src="up_src_{$upload_item_id}" name="up_file_{$upload_item_id}" />
		<input type="text" id="up_src_{$upload_item_id}" name="up_src_{$upload_item_id}" value="" style="display:none" />
		<div class="img_box_image"><img id="up_img_{$upload_item_id}" src="/img/upload_plus.png" /></div>
		<div class="img_box_desc">
			<p class="fl">{$upload_item_name}</p>
			<p class="fr">{$upload_item_size}</p>
		</div>
	</div>
</div>