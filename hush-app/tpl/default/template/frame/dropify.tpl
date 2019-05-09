<link href="/app/dropify/css/dropify.min.css" rel="stylesheet" type="text/css" />
<script src="/app/dropify/js/dropify.min.js" type="text/javascript"></script>
<input type="file" class="dropify" name="{$file_obj}" />
<div style="clear:both;height:10px;"></div>
<progress id="progressBar" value="0" max="100" style="width:100%"></progress>
<span id="percentBox"></span>
<div style="clear:both;height:10px;"></div>
<input type="button" value="开始上传" id="upload_btn_{$file_obj}" />
<script type="text/javascript">
$(function(){
	$('#upload_btn_{$file_obj}').click(function(){
		$(this).attr('disabled', true);
	    var fileObj = $('input[name="{$file_obj}"]')[0].files[0];
	    var fileApi = '{$file_api}';
	    // 判断是否选择文件
	    if (!fileObj || fileObj == 'undefined') {
	    	$(this).attr('disabled', false);
	    	layer.msg("请选择文件!");
	    	return false;
	    }
	    // FormData 对象
	    var form = new FormData();
	    form.append("_file", fileObj);
	    // XMLHttpRequest 对象
	    var xhr = new XMLHttpRequest();
	    xhr.open("POST", fileApi, true);
	    xhr.onreadystatechange = function(){
            if (xhr.readyState==4) {
                if (xhr.status == 200) {
                    var responseText = xhr.responseText;
                    if (responseText) {
                    	var data = eval('('+responseText+')');
                    	if (data.err == '0') {
                    		layer.msg("上传完成!");
                    	} else {
                    		layer.msg("上传失败!");
                    	}
                    	setTimeout(function(){
                    		location.reload();
                    	}, 1000);
                    }
                }
            }
        }
	    // 实现进度条功能 
	    xhr.upload.addEventListener("progress", function(e){
	        if (e.lengthComputable) {
	            $('#progressBar')[0].max = e.total;
	            $('#progressBar')[0].value = e.loaded;
	            $('#percentBox').html(Math.round(e.loaded / e.total * 100)+"%");
	        }
	    }, false);  
	    xhr.send(form);  
	});
});
</script>