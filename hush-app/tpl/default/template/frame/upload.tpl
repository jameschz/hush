<link rel="stylesheet" type="text/css" href="{$_host_s}/app/upload/uploadify.css">
<script type="text/javascript" src="{$_host_s}/app/upload/jquery.uploadify.min.js"></script>
<script type="text/javascript">
$(function() {
	$('#file_upload_btn_{$name}').uploadify({
		'swf'      : '/app/upload/uploadify.swf',
		'uploader' : '{$_host_u}/hush/upload/from/admin',
		'formData' : { 'sid' : '{$_sid}', 'type' : '{$type}' },
		'fileTypeExts' : '*.jpg;*.png;*.gif;*.jpeg',
		'onUploadSuccess' : function(file, data, response) {
			try {
				var data = eval('(' + data + ')');
				if (data.code == 1) {
					$('#file_upload_pic_{$name}').attr('src', data.msg);
					$('input[name={$name}]').attr('value', data.msg);
				} else {
					alert(data.msg);
				}
			} catch (e) {
				alert(data);
			}
		},
		'onUploadError' : function(file, errorCode, errorMsg) {
			alert(errorCode+' : '+errorMsg)
		},
		'onSelectError' : function(file, errorCode, errorMsg) {
			alert(errorCode+' : '+errorMsg)
		}
	});
});
</script>
<div class="uploaddiv">
<div style="float:left;margin-right:10px;">
<img id="file_upload_pic_{$name}" src="{if $value}{$value}{else}{$_phost}/img/no_image.png{/if}" class="uploadpic" />
</div>
<div style="float:left">
<input type="hidden" name="{$name}" value="{if $value}{$value}{/if}" />
<input type="file" id="file_upload_btn_{$name}" />
</div>
</div>