(function($){

	// string
	$.string = {
		striptags : function (s) {
			return s.replace(/<[^>].*?>/g,"");
		}
	}
	
	// test funcs
	$.test = {
		openwin : function (s) {
			var screen = arguments[1] || 0;
			if (screen == 0) {
				window.open(s, 'preview','height=800,width=400,top=0,left=0,toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=no');
			} else {
				window.open(s, 'preview','height=480,width=800,top=0,left=0,toolbar=no,menubar=no,scrollbars=yes,resizable=no,location=no,status=no');
			}
		}
	}
	
	// popup box
	$.overlay = {
		show : function () {
			var e = arguments[0] || null;
			if (e) {
				$('div.main').block({
					topOffset : this._getTopOffset(),
					overlayCSS : this._getOverlayCss(),
					css : { padding : '0px', width : '70%'},
					message : e,
					onBlock : function () {
						$('.blockOverlay').css({
							height : $(document).height()
						});
					}
				});
			} else {
				$('div.main').block({
					topOffset : this._getTopOffset(),
					overlayCSS : this._getOverlayCss(),
					css : { padding : '20px', width : '50%'},
					message : '<h1><img src="/img/busy.gif" /> &nbsp; Processing ...</h1>'
				});
			}
		},
		frame : function (url, callback) {
			$('div.main').block({
				topOffset : this._getTopOffset(),
				overlayCSS : this._getOverlayCss(),
				css : { padding : '0px', width : '70%'},
				message : '<iframe id="overlayInnerIframe" style="width:100%;height:500px;border:none;overflow-x:hidden" src="' + url + '"></iframe>',
				onBlock : function () {
					$('iframe#overlayInnerIframe').load(callback);
				}
			});
		},
		close: function () {
			var timeout = arguments[0] || 0;
			var unfresh = arguments[1] || 0;
			setTimeout(function(){
				$('div.main').unblock();
				if (!unfresh) window.location.reload();
			}, timeout);
		},
		// private 
		_getTopOffset: function () {
			w_h = $(window).height();
			p_t = parseInt(w_h / 2 - 40);
			return p_t;
		},
		_getOverlayCss: function () {
			return {
				opacity : 0.5,
				backgroundColor : '#333333',
				height : $(document).height()
			};
		}
	}
	
	// form funcs
	$.form = {
		select: function (selector) {
			var select_ele = $(selector);
			select_ele.focus(function(){
				$(this).select();
			});
		},
		jumpto: function (url, msg) {
			location.href = encodeURI(url)
		},
		confirm: function (url, msg) {
			if (confirm(msg)) {
				location.href = encodeURI(url);
			}
		},
		addtext: function (tid, str) {
			var textArea = document.getElementById(tid);
			var textLength = textArea.value.length;
			textArea.focus();
			if(typeof document.selection != "undefined") {
				document.selection.createRange().text = str;
				document.selection.createRange().select();
			} else {
				var startPos = textArea.selectionStart;
				var endPos = startPos + str.length;
				textArea.value = textArea.value.substr(0, textArea.selectionStart) + str + textArea.value.substring(textArea.selectionEnd, textLength);
				textArea.setSelectionRange(startPos, endPos); 
			}
		},
		filter: function (options) {
			var filter_input = options.filter_input;
			var filter_rows = options.filter_rows;
			var filter_val = options.filter_val;
			$(filter_input).keyup(function(){
				input = $(this).val();
				if (input == '') {
					$(filter_rows).show();
				} else {
					$(filter_rows).hide();
					filter_val.each(function(){
						var text = $.string.striptags($(this).html());
						var regexp = new RegExp("^" + input);
						if (regexp.test(text)) {
							$(this).parents("tr").show();
						}
					});
				}
			});
		}
	}
	
	// toggle exts
	$.toggle = {
		input: function (selector) {
			var toggle_ele = $(selector);
			var str_old = toggle_ele.val();
			var str_new = "";
			toggle_ele.focus(function(){
				str_new = $(this).val();
				if (str_new == str_old || str_new == '') {
					str_new = $(this).val();
					$(this).val('');
				}
			}).blur(function(){
				if ($(this).val() == '') {
					$(this).val(str_old);
				}
			});
		},
		textarea: function (selector) {
			var toggle_ele = $(selector);
			var str_old = toggle_ele.get(0).value;
			var str_new = "";
			toggle_ele.focus(function(){
				str_new = $(this).get(0).value;
				if (str_new == str_old || str_new == '') {
					str_new = $(this).get(0).value;
					$(this).get(0).value = '';
				}
			}).blur(function(){
				if ($(this).get(0).value == '') {
					$(this).get(0).value = str_old;
				}
			});
		}
	}
	
})(jQuery);

$(document).ready(function(){
	
	// tlist table styles
	$('.tlist').find('tbody tr').mouseover(function(){
		$(this).css({'background':'#D9E8F5'});
	}).mouseout(function(){
		$(this).css({'background':'#FFFFFF'});
	});
	
});

Date.prototype.Format = function (fmt) { //author: meizz   
	var o = {  
		"M+": this.getMonth() + 1, //月份   
		"d+": this.getDate(), //日   
		"H+": this.getHours(), //小时   
		"m+": this.getMinutes(), //分   
		"s+": this.getSeconds(), //秒   
		"q+": Math.floor((this.getMonth() + 3) / 3), //季度   
		"S": this.getMilliseconds() //毫秒   
	};  
	if (/(y+)/.test(fmt)) fmt = fmt.replace(RegExp.$1, (this.getFullYear() + "").substr(4 - RegExp.$1.length));  
	for (var k in o) {
		if (new RegExp("(" + k + ")").test(fmt)) {
			fmt = fmt.replace(RegExp.$1, (RegExp.$1.length == 1) ? (o[k]) : (("00" + o[k]).substr(("" + o[k]).length)));  
		}
	}
	return fmt;  
}