<iframe id="tinymce_upload_form_target" name="tinymce_upload_form_target" style="display:none"></iframe>
<form id="tinymce_upload_form" action="{$_host_u}/hush/upload/from/tinymce" target="tinymce_upload_form_target" method="post" enctype="multipart/form-data" style="display:none">
<input name="Filedata" type="file" onchange="$('#tinymce_upload_form').submit();this.value='';">
</form>
<script src="{$_host_s}/app/tinymce/tinymce.min.js" type="text/javascript"></script>
<script src="{$_host_s}/js/editor.js?v={$_ver}" type="text/javascript"></script>
<script type="text/javascript">
var cache_ns = '{$_puid}';
</script>