{include file="frame/head.tpl"}

<div style="display-inline:block;">
    <input type="text" id="cache_data" value="" />
    <input type="button" value="设置缓存数据" id="set_cache_btn" />
</div>
<script>
$(function(){
	$('#set_cache_btn').click(function(){
		var cache_data = $('#cache_data').val();
		if (cache_data == '') {
			alert('请输入缓存数据');
			return false;
		}
		$.post('/demo/setcache', {
			'cache_id' : 'cache_test',
			'cache_data' : cache_data
		}, function(r){
			if (r.err == '0') {
				alert('请求缓存设置API：/demo/setcache，设置最新数据为：'+r.data.data);
				location.reload();
			}
		}, 'json')
	});
});
</script>

{include file="frame/error.tpl"}

<pre style="display-inline:block;padding-top:10px;">
<h3>通过缓存获取：</h3>
{if $result}{$result}{else}NULL{/if}
</pre>

{include file="frame/foot.tpl"}