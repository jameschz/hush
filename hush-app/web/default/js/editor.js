$(function(){
	var editor_version = '4.6.7_171220';
	if ($('textarea.storytext').length>0) {
		tinymce.init({
			skin: 'lightgray',
			cache_suffix: '?v='+editor_version,
			selector : 'textarea.storytext',
			plugins : 'advlist autolink link image lists charmap print code table textcolor colorpicker fullscreen wxai ylink ymedia yimage',
			toolbar: "undo redo | forecolor | bold italic removeformat | alignleft aligncenter alignright | ylink ymedia yimage wxai | fullscreen",
			fontsize_formats: "8pt 10pt 12pt 14pt 16pt 20pt 24pt 36pt",
			width: 700,
			height: 300,
			menubar: false,
			convert_urls: false,
			media_poster: false,
			media_dimensions: false,
			media_live_embeds: true,
			init_instance_callback: function(ed) {},
			file_browser_callback: function(fname, url, type, win) {
			    if(type=='image') $('#tinymce_upload_form input').click();
			    if(type=='file') alert('暂时只支持图片上传！');
			}
		});
	}
	if ($('textarea.richtext').length>0) {
		tinymce.init({
			skin: 'lightgray',
			cache_suffix: '?v='+editor_version,
			selector : 'textarea.richtext',
			plugins : 'advlist autolink link image lists charmap print code table textcolor colorpicker fullscreen wxai ylink ymedia',
			toolbar: "undo redo | forecolor | bold italic removeformat | alignleft aligncenter alignright | ylink image | fullscreen",
			fontsize_formats: "8pt 10pt 12pt 14pt 16pt 20pt 24pt 36pt",
			width: 700,
			height: 300,
			menubar: false,
			convert_urls: false,
			media_poster: false,
			media_dimensions: false,
			media_live_embeds: true,
			init_instance_callback: function(ed) {},
			file_browser_callback: function(fname, url, type, win) {
			    if(type=='image') $('#tinymce_upload_form input').click();
			    if(type=='file') alert('暂时只支持图片上传！');
			}
		});
	}
	if ($('textarea.flattext').length>0) {
        var editor = tinymce.init({
        	skin: 'lightgray',
        	cache_suffix: '?v='+editor_version,
            selector : 'textarea.flattext',
            plugins : 'advlist autolink link image lists charmap print code table textcolor colorpicker fullscreen wxai ylink ymedia',
            toolbar: "undo redo | forecolor | bold italic removeformat | alignleft aligncenter alignright | fullscreen",
            fontsize_formats: "8pt 10pt 12pt 14pt 16pt 20pt 24pt 36pt",
            width: 700,
            height: 300,
            menubar: false,
            convert_urls : false,
            init_instance_callback: function(ed) {},
            file_browser_callback: function(fname, url, type, win) {
                if(type=='image') $('#tinymce_upload_form input').click();
                if(type=='file') alert('暂时只支持图片上传！');
            }
        });
    }
	if ($('textarea.autosave').length>0 && cache_ns) {
		$('textarea.autosave').each(function(){
			var cache_el = $(this);
			var cache_id = cache_ns+'-'+cache_el.attr('id');
			var cache_tips = cache_id+'-tips';
			// read cache
	        var cache_data = localStorage.getItem(cache_id);
	        if (cache_data) {
	        	cache_el.val(cache_data);
	            setTimeout(function(){
	                var tips_text = '已读取历史保存的文章，系统每 10 秒会自动保存！';
	                cache_el.prev().before('<p id="'+cache_tips+'" class="item_tips"><b>'+tips_text+'</b><a href="javascript:localStorage.removeItem(\''+cache_id+'\');location.reload();">清除缓存并刷新</a></p>');
	            },1000);
	        }
		    // auto save
			setInterval(function(){
			    var cache_ifr_id = cache_el.attr('id')+'_ifr';
			    var cache_editor = $('#'+cache_ifr_id).contents().find('body');
		        var cache_editor_data = cache_editor.html();
		        localStorage.setItem(cache_id, cache_editor_data);
				var nowTime = new Date();
				var tips_text = '文章已自动保存于 '+nowTime.Format("yyyy-MM-dd HH:mm:ss");
				if ($('#'+cache_tips).length>0) {
					$('#'+cache_tips).find('b').html(tips_text);
				} else {
					cache_el.prev().before('<p id="'+cache_tips+'" class="item_tips"><b>'+tips_text+'</b><a href="javascript:localStorage.removeItem(\''+cache_id+'\');location.reload();">清除缓存并刷新</a></p>');
				}
			},10000);
		});
	}
});