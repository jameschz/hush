/* 使用方法：
<div><img id="imgPr" width="120" height="120" /></div>
<input type="file" id="up" />
把需要进行预览的img标签外 套一个DIV 然后给上传控件ID给予uploadPreview事件
$("#up").uploadPreview({ img: "imgPr", width: 120, height: 120, types: ["gif", "jpeg", "jpg", "bmp", "png"], callback: function () { }});
*/
jQuery.fn.extend({
    uploadPreview: function (opts) {
        var _self = this,
            _this = $(this);
        opts = jQuery.extend({
            img: "id",
            width: 100,
            height: 100,
            types: ["gif", "jpg", "png"],
            maxsize: 1024 * 1024 * 8, // 原图不能大于8M，否则压缩了也很大
            callback: function () {},
            readfile: function () {}
        }, opts || {});
        _self.getObjectURL = function (file) {
            var url = null;
            if (window.createObjectURL != undefined) {
                url = window.createObjectURL(file)
            } else if (window.URL != undefined) {
                url = window.URL.createObjectURL(file)
            } else if (window.webkitURL != undefined) {
                url = window.webkitURL.createObjectURL(file)
            }
            return url
        };
        _this.change(function () {
            if (this.value) {
                if (!RegExp("\.(" + opts.types.join("|") + ")$", "i").test(this.value.toLowerCase())) {
                    alert("图片类型只能是" + opts.types.join("，") + "！");
                    this.value = "";
                    return false;
                }
                var file_data = this.files[0];
                var file_size = file_data.size;
                if (file_size > opts.maxsize) {
                    alert("图片大小必须小于" + parseInt(opts.maxsize/(1024*1024)) + "M！");
                    this.value = "";
                    return false;
                }
                // 使用FileReader
                var reader = new FileReader();
                reader.readAsDataURL(file_data);
            	reader.onload = function(e){
            		var image = new Image();
            		image.onload = function(){
                        var old_w = parseInt(image.width);
                        var old_h = parseInt(image.height);
                        var scale = old_w / old_h;
                        // 压缩图片质量
                        var new_w = old_w < 1080 ? old_w : 1080;
                        var new_h = parseInt(new_w / scale);
                        canvas = document.createElement("canvas"),
                        canvas.width = new_w;
                        canvas.height = new_h;
                        drawer = canvas.getContext("2d");
                        drawer.drawImage(image,0,0,new_w,new_h);
                        // 回调FileReader方法
                        var imageCompress = canvas.toDataURL("image/jpeg", 0.8); // 80%
                        opts.readfile(this, imageCompress);
            		}
            		image.src = this.result;
            	}
                // 使用createObjectURL
                var old_img_src = $("#" + opts.img).attr('src');
                if (/msie/.test(navigator.userAgent.toLowerCase())) {
                    try {
                        $("#" + opts.img).attr('src', _self.getObjectURL(file_data))
                    } catch (e) {
                        var src = "";
                        var obj = $("#" + opts.img);
                        var div = obj.parent("div")[0];
                        _self.select();
                        if (top != self) {
                            window.parent.document.body.focus()
                        } else {
                            _self.blur()
                        }
                        src = document.selection.createRange().text;
                        document.selection.empty();
                        obj.hide();
                        obj.parent("div").css({
                            'filter': 'progid:DXImageTransform.Microsoft.AlphaImageLoader(sizingMethod=scale)',
                            'width': opts.width + 'px',
                            'height': opts.height + 'px'
                        });
                        div.filters.item("DXImageTransform.Microsoft.AlphaImageLoader").src = src
                    }
                } else {
                    $("#" + opts.img).attr('src', _self.getObjectURL(file_data))
                }
                // 回调createObjectURL方法
                var img_src = $("#" + opts.img).attr('src');
                opts.callback(this, img_src, old_img_src);
            }
        })
    }
});