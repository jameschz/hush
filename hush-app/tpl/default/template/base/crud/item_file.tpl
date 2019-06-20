<link href="/app/cropper/cropper.min.css" rel="stylesheet" type="text/css" />
<script src="/app/cropper/cropper.min.js" type="text/javascript"></script>
<script src="/js/jquery.up.js" type="text/javascript"></script>
{if $from eq 'add'}{* add *}
	{foreach $v.files as $k1 => $v1}
        {if $v1.type eq 'imagecrop'}
        <div id="crop_box_{$k}_{$k1}" class="crop_box_div">
            <div class="crop_box_txt_b">滚动鼠标滚轮可放大缩小图片</div>
            <div id="crop_btn_back_{$k}_{$k1}" class="crop_box_btn_l">返回</div>
            <div id="crop_btn_save_{$k}_{$k1}" class="crop_box_btn_r">保存</div>
            <div><img id="crop_img_{$k}_{$k1}" /></div>
        </div>
        <div class="img_box">
            <div class="img_box_in">
                <input type="file" id="uf_{$k}_{$k1}" />
                <input type="hidden" id="ut_{$k}_{$k1}" name="{$k}[{$k1}]" />
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="{if $item._data.$k.$k1.value}{$item._data.$k.$k1.value}{else}/img/upload_plus.png{/if}" /></div>
                <div class="img_box_desc">
                    <p class="fl">{$v1.name}</p>
                    <p class="fr">{$v1.size}</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            var img_w = 1;
            var img_h = 1;
            var img_resize = false;
            var img_size = '{$v1.size}';
            if (img_size) {
                img_resize = false;
                img_w = parseInt(img_size.split('x')[0]);
                img_h = parseInt(img_size.split('x')[1]);
            } else {
                img_resize = true;
            }
            $("#uf_{$k}_{$k1}").uploadPreview({
                img : "up_{$k}_{$k1}",
                callback : function(file_obj, img_src, old_img_src, img_meta){
                    var crop_box = $('#crop_box_{$k}_{$k1}');
                    var crop_img = $('#crop_img_{$k}_{$k1}');
                    var crop_btn_back = $('#crop_btn_back_{$k}_{$k1}');
                    var crop_btn_save = $('#crop_btn_save_{$k}_{$k1}');
                    crop_box.css({
                        'width' : $(window).width(),
                        'height' : $(window).height()
                    });
                    crop_box.show();
                    crop_img.attr('src', img_src);
                    crop_img.css('height', $(window).height());
                    crop_img.cropper({
                        viewMode: 1,
                        aspectRatio: img_w/img_h,
                        dragMode: 'move',
                        autoCropArea: 0.75,
                        restore: false,
                        guides: true,
                        highlight: false,
                        cropBoxMovable: false,
                        cropBoxResizable: img_resize,
                        minContainerHeight: $(window).height()
                    });
                    crop_img.cropper('replace', img_src);
                    crop_btn_back.click(function(){
                    	$('#up_{$k}_{$k1}').attr('src', old_img_src);
                    	file_obj.value = "";
                        crop_box.hide();
                    });
                    crop_btn_save.click(function(){
                        var canvas = crop_img.cropper("getCroppedCanvas");
                        var result = canvas.toDataURL(img_meta, 0.8);
                        $('#up_{$k}_{$k1}').attr('src', result);
                        $('#ut_{$k}_{$k1}').val(result);
                        crop_box.hide();
                    });
                }
            });
        });
        </script>
		{elseif $v1.type eq 'image'}
		<div class="img_box">
			<div class="img_box_in">
				<input type="file" id="uf_{$k}_{$k1}" />
                <input type="hidden" id="ut_{$k}_{$k1}" name="{$k}[{$k1}]" />
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="{if $item._data.$k.$k1.value}{$item._data.$k.$k1.value}{else}/img/upload_plus.png{/if}" /></div>
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
            $("#uf_{$k}_{$k1}").uploadPreview({
                img : "up_{$k}_{$k1}",
                readfile : function(file_obj, file_data){
                	$('#ut_{$k}_{$k1}').val(file_data);
                }
            });
		});
		</script>
		{elseif $v1.type eq 'audio'}
		<div class="img_box">
			<div class="img_box_in">
				<input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="{if $item._data.$k.$k1.value}/img/upload_audio.png{else}/img/upload_plus.png{/if}" /></div>
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#uf_{$k}_{$k1}").change(function(){
				$('#up_{$k}_{$k1}').attr('src', '/img/upload_audio.png');
			});
		});
		</script>
		{elseif $v1.type eq 'video'}
		<div class="img_box">
			<div class="img_box_in">
				<input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="{if $item._data.$k.$k1.value}/img/upload_video.png{else}/img/upload_plus.png{/if}" /></div>
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#uf_{$k}_{$k1}").change(function(){
				$('#up_{$k}_{$k1}').attr('src', '/img/upload_video.png');
			});
		});
		</script>
		{else}
        <div class="img_box">
            <div class="img_box_in">
                <input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="{if $item._data.$k.$k1.value}/img/upload_image.png{else}/img/upload_plus.png{/if}" /></div>
                <div class="img_box_desc">
                    <p class="fl">{$v1.name}</p>
                    <p class="fr">{$v1.size}</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            $("#uf_{$k}_{$k1}").change(function(){
                $('#up_{$k}_{$k1}').attr('src', '/img/upload_image.png');
            });
        });
        </script>
		{/if}
	{/foreach}
	
{elseif $from eq 'edit'}{* edit *}
	{foreach $v.files as $k1 => $v1}
        {if $v1.type eq 'imagecrop'}
        <div id="crop_box_{$k}_{$k1}" class="crop_box_div">
            <div class="crop_box_txt_b">滚动鼠标滚轮可放大缩小图片</div>
            <div id="crop_btn_back_{$k}_{$k1}" class="crop_box_btn_l">返回</div>
            <div id="crop_btn_save_{$k}_{$k1}" class="crop_box_btn_r">保存</div>
            <div><img id="crop_img_{$k}_{$k1}" /></div>
        </div>
        <div class="img_box">
            <div class="img_box_in">
                <input type="file" id="uf_{$k}_{$k1}" />
                <input type="hidden" id="ut_{$k}_{$k1}" name="{$k}[{$k1}]" />
                {if $item._data.$k.$k1.value}
                <div class="img_box_close" id="ud_{$k}_{$k1}" _id="{$k1}" _file_id="{$item[$bps.pkey]}" _file_col="{$k}" _file_name="{$k1}"><i class="icon icon-cross"></i></div>
                <div class="img_box_copy" id="cp_{$k}_{$k1}"><i class="icon icon-copy"></i></div>
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="{$item._data.$k.$k1.value}" /></div>
                {else}
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_plus.png" /></div>
                <div class="img_box_desc"><p>{$v1.name}</p></div>
                {/if}
                <div class="img_box_desc">
                    <p class="fl">{$v1.name}</p>
                    <p class="fr">{$v1.size}</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
        	var img_w = 1;
        	var img_h = 1;
        	var img_aspect = null;
        	var img_resize = false;
        	var img_size = '{$v1.size}';
        	if (img_size) {
                img_resize = false;
                img_w = parseInt(img_size.split('x')[0]);
                img_h = parseInt(img_size.split('x')[1]);
                img_aspect = img_w / img_h;
        	} else {
        		img_resize = true;
        	}
        	$("#uf_{$k}_{$k1}").uploadPreview({
        	    img : "up_{$k}_{$k1}",
        	    callback : function(file_obj, img_src, old_img_src, img_meta){
        	    	var crop_box = $('#crop_box_{$k}_{$k1}');
        	    	var crop_img = $('#crop_img_{$k}_{$k1}');
        	    	var crop_btn_back = $('#crop_btn_back_{$k}_{$k1}');
        	    	var crop_btn_save = $('#crop_btn_save_{$k}_{$k1}');
        	    	crop_box.css({
        	    		'width' : $(window).width(),
        	    		'height' : $(window).height()
        	    	});
        	    	crop_box.show();
        	    	crop_img.attr('src', img_src);
        	    	crop_img.css('height', $(window).height());
        	    	crop_img.cropper({
        	            viewMode: 1,
        	            aspectRatio: img_aspect,
        	            dragMode: 'move',
        	            autoCropArea: 0.75,
        	            restore: false,
        	            guides: true,
        	            highlight: false,
        	            cropBoxMovable: false,
        	            cropBoxResizable: img_resize,
        	            minContainerHeight: $(window).height()
        	        });
        	    	crop_img.cropper('replace', img_src);
        	    	crop_btn_back.click(function(){
        	    		$('#up_{$k}_{$k1}').attr('src', old_img_src);
        	    		file_obj.value = "";
        	    		crop_box.hide();
        	    	});
        	    	crop_btn_save.click(function(){
        	        	var canvas = crop_img.cropper("getCroppedCanvas");
        	        	var result = canvas.toDataURL(img_meta, 0.8);
        	        	$('#up_{$k}_{$k1}').attr('src', result);
        	        	$('#ut_{$k}_{$k1}').val(result);
        	        	crop_box.hide();
        	        });
        	    }
        	});
            $("#ud_{$k}_{$k1}").click(function(){
                $.post('{$action_path}/a/delete', {
                    '_file_id' : $(this).attr('_file_id'),
                    '_file_col' : $(this).attr('_file_col'),
                    '_file_name' : $(this).attr('_file_name'),
                }, function(data){
                    var json = eval('('+data+')');
                    if (json.err == 0) {
                        location.reload();
                    } else {
                        layer.msg(json.msg);
                    }
                });
            });
            $("#cp_{$k}_{$k1}").click(function(){
                var img_url = $("#up_{$k}_{$k1}").attr('src');
                layer.alert(img_url);
            });
        });
        </script>
		{elseif $v1.type eq 'image'}
		<div class="img_box">
			<div class="img_box_in">
                <input type="file" id="uf_{$k}_{$k1}" />
                <input type="hidden" id="ut_{$k}_{$k1}" name="{$k}[{$k1}]" />
				{if $item._data.$k.$k1.value}
				<div class="img_box_close" id="ud_{$k}_{$k1}" _id="{$k1}" _file_id="{$item[$bps.pkey]}" _file_col="{$k}" _file_name="{$k1}"><i class="icon icon-cross"></i></div>
				<div class="img_box_copy" id="cp_{$k}_{$k1}"><i class="icon icon-copy"></i></div>
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="{$item._data.$k.$k1.value}" /></div>
				{else}
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_plus.png" /></div>
				<div class="img_box_desc"><p>{$v1.name}</p></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#uf_{$k}_{$k1}").uploadPreview({
			    img : "up_{$k}_{$k1}",
                readfile : function(file_obj, file_data){
                    $('#ut_{$k}_{$k1}').val(file_data);
                }
			});
			$("#ud_{$k}_{$k1}").click(function(){
				$.post('{$action_path}/a/delete', {
					'_file_id' : $(this).attr('_file_id'),
					'_file_col' : $(this).attr('_file_col'),
					'_file_name' : $(this).attr('_file_name'),
				}, function(data){
					var json = eval('('+data+')');
					if (json.err == 0) {
						location.reload();
					} else {
						layer.msg(json.msg);
					}
				});
			});
			$("#cp_{$k}_{$k1}").click(function(){
				var img_url = $("#up_{$k}_{$k1}").attr('src');
				layer.alert(img_url);
			});
		});
		</script>
		{elseif $v1.type eq 'audio'}
		<div class="img_box">
			<div class="img_box_in">
				<input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
				{if $item._data.$k.$k1.value}
				<div class="img_box_close" id="ud_{$k}_{$k1}" _id="{$k1}" _file_id="{$item[$bps.pkey]}" _file_col="{$k}" _file_name="{$k1}"><i class="icon icon-cross"></i></div>
				<div class="img_box_copy" id="cp_{$k}_{$k1}"><i class="icon icon-copy"></i></div>
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_audio.png" data-audio="{$item._data.$k.$k1.value}" /></div>
				{else}
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_plus.png" /></div>
				<div class="img_box_desc"><p>{$v1.name}</p></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#uf_{$k}_{$k1}").change(function(){
				$('#up_{$k}_{$k1}').attr('src', '/img/upload_audio.png');
			});
			$("#ud_{$k}_{$k1}").click(function(){
				$.post('{$action_path}/a/delete', {
					'_file_id' : $(this).attr('_file_id'),
					'_file_col' : $(this).attr('_file_col'),
					'_file_name' : $(this).attr('_file_name'),
				}, function(data){
					var json = eval('('+data+')');
					if (json.err == 0) {
						location.reload();
					} else {
						layer.msg(json.msg);
					}
				});
			});
			$("#cp_{$k}_{$k1}").click(function(){
				var data_audio = $("#up_{$k}_{$k1}").attr('data-audio');
				layer.alert(data_audio);
				/*var player = '<video src="'+data_audio+'" controls="controls"></video>';
				layer.open({
					type: 1,
					title: false,
					closeBtn: 0,
					shadeClose: true,
					content: player
				});*/
			});
		});
		</script>
		{elseif $v1.type eq 'video'}
		<div class="img_box">
			<div class="img_box_in">
				<input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
				{if $item._data.$k.$k1.value}
				<div class="img_box_close" id="ud_{$k}_{$k1}" _id="{$k1}" _file_id="{$item[$bps.pkey]}" _file_col="{$k}" _file_name="{$k1}"><i class="icon icon-cross"></i></div>
				<div class="img_box_copy" id="cp_{$k}_{$k1}"><i class="icon icon-copy"></i></div>
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_video.png" data-video="{$item._data.$k.$k1.value}" /></div>
				{else}
				<div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_plus.png" /></div>
				<div class="img_box_desc"><p>{$v1.name}</p></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#uf_{$k}_{$k1}").change(function(){
				$('#up_{$k}_{$k1}').attr('src', '/img/upload_audio.png');
			});
			$("#ud_{$k}_{$k1}").click(function(){
				$.post('{$action_path}/a/delete', {
					'_file_id' : $(this).attr('_file_id'),
					'_file_col' : $(this).attr('_file_col'),
					'_file_name' : $(this).attr('_file_name'),
				}, function(data){
					var json = eval('('+data+')');
					if (json.err == 0) {
						location.reload();
					} else {
						layer.msg(json.msg);
					}
				});
			});
			$("#cp_{$k}_{$k1}").click(function(){
				var data_video = $("#up_{$k}_{$k1}").attr('data-video');
				layer.alert(data_video);
				/*var player = '<video src="'+data_video+'" controls="controls"></video>';
				layer.open({
					type: 1,
					title: false,
					closeBtn: 0,
					shadeClose: true,
					content: player
				});*/
			});
		});
		</script>
		{else}
        <div class="img_box">
            <div class="img_box_in">
                <input type="file" id="uf_{$k}_{$k1}" name="{$k}[{$k1}]" />
                {if $item._data.$k.$k1.value}
                <div class="img_box_close" id="ud_{$k}_{$k1}" _id="{$k1}" _file_id="{$item[$bps.pkey]}" _file_col="{$k}" _file_name="{$k1}"><i class="icon icon-cross"></i></div>
                <div class="img_box_copy" id="cp_{$k}_{$k1}"><i class="icon icon-copy"></i></div>
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_image.png" data-files="{$item._data.$k.$k1.value}" /></div>
                {else}
                <div class="img_box_image"><img id="up_{$k}_{$k1}" src="/img/upload_plus.png" /></div>
                <div class="img_box_desc"><p>{$v1.name}</p></div>
                {/if}
                <div class="img_box_desc">
                    <p class="fl">{$v1.name}</p>
                    <p class="fr">{$v1.size}</p>
                </div>
            </div>
        </div>
        <script type="text/javascript">
        $(function(){
            $("#uf_{$k}_{$k1}").change(function(){
                $('#up_{$k}_{$k1}').attr('src', '/img/upload_image.png');
            });
            $("#ud_{$k}_{$k1}").click(function(){
                $.post('{$action_path}/a/delete', {
                    '_file_id' : $(this).attr('_file_id'),
                    '_file_col' : $(this).attr('_file_col'),
                    '_file_name' : $(this).attr('_file_name'),
                }, function(data){
                    var json = eval('('+data+')');
                    if (json.err == 0) {
                        location.reload();
                    } else {
                        layer.msg(json.msg);
                    }
                });
            });
            $("#cp_{$k}_{$k1}").click(function(){
            	var data_files = $("#up_{$k}_{$k1}").attr('data-files');
                layer.alert(data_files);
            });
        });
        </script>
		{/if}
	{/foreach}

{else}{* display *}
	{foreach $v.files as $k1 => $v1}
		{if $v1.type eq 'image'}
		<div class="img_box">
			<div class="img_box_in">
				<div class="img_box_image">
				{if $item._data.$k.$k1.value}
				<img id="pop_{$k1}" src="{$item._data.$k.$k1.value}" style="cursor:pointer;width:100%;height:100%;" /></div>
				{else}
				<img src="/img/no_image.png" style="width:100%;height:100%;" /></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
			</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#pop_{$k1}").click(function(){
				layer.open({
				    type: 1,
				    title: '',
				    closeBtn: 0,
				    shadeClose: true,
				    content: $(this).parent().html(),
				});
			});
		});
		</script>
		{elseif $v1.type eq 'audio'}
		<div class="img_box">
				<div class="img_box_image">
				{if $item._data.$k.$k1.value}
				<img id="pop_{$k1}" src="/img/upload_audio.png" data-audio="{$item._data.$k.$k1.value}" style="cursor:pointer;width:100%;height:100%;" /></div>
				{else}
				<img src="/img/no_image.png" style="width:100%;height:100%;" /></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#pop_{$k1}").click(function(){
				var data_audio = $("#pop_{$k1}").attr('data-audio');
				var player = '<video src="'+data_audio+'" controls="controls"></video>';
				layer.open({
					type: 1,
					title: false,
					closeBtn: 0,
					shadeClose: true,
					content: player
				});
			});
		});
		</script>
		{elseif $v1.type eq 'video'}
		<div class="img_box">
				<div class="img_box_image">
				{if $item._data.$k.$k1.value}
				<img id="pop_{$k1}" src="/img/upload_video.png" data-video="{$item._data.$k.$k1.value}" style="cursor:pointer;width:100%;height:100%;" /></div>
				{else}
				<img src="/img/no_image.png" style="width:100%;height:100%;" /></div>
				{/if}
				<div class="img_box_desc">
					<p class="fl">{$v1.name}</p>
					<p class="fr">{$v1.size}</p>
				</div>
		</div>
		<script type="text/javascript">
		$(function(){
			$("#pop_{$k1}").click(function(){
				var data_video = $("#pop_{$k1}").attr('data-video');
				var player = '<video src="'+data_video+'" controls="controls"></video>';
				layer.open({
					type: 1,
					title: false,
					closeBtn: 0,
					shadeClose: true,
					offset: '0px',
					content: player
				});
			});
		});
		</script>
		{else}
        <div class="img_box">
                <div class="img_box_image">
                {if $item._data.$k.$k1.value}
                <img id="pop_{$k1}" src="/img/upload_image.png" data-video="{$item._data.$k.$k1.value}" style="cursor:pointer;width:100%;height:100%;" /></div>
                {else}
                <img src="/img/no_image.png" style="width:100%;height:100%;" /></div>
                {/if}
                <div class="img_box_desc">
                    <p class="fl">{$v1.name}</p>
                    <p class="fr">{$v1.size}</p>
                </div>
        </div>
		{/if}
	{/foreach}
{/if}