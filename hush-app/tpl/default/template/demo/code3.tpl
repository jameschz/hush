{include file="frame/head.tpl"}

<div style="display-inline:block;">
    <input type="button" value="通过缓存设置" id="set_cache_btn" />
    <input type="button" value="通过会话设置" id="set_session_btn" />
</div>
<script>
$(function(){
    $('#set_cache_btn').click(function(){
        $.post('/demo/settoken', {
            'token_id' : '{$token_id}'
        }, function(r){
            if (r.err == '0') {
                alert('请求缓存设置API：/demo/settoken，设置最新时间为：'+r.data.time);
                location.reload();
            }
        }, 'json')
    });
    $('#set_session_btn').click(function(){
    	alert('通过会话设置最新时间，点击确定刷新页面');
    	location.href='/demo/code3?act=1';
    });
});
</script>

{include file="frame/error.tpl"}

<pre style="display-inline:block;padding-top:10px;">
<h3>通过缓存获取：</h3>
{$result}
</pre>

<pre style="display-inline:block;padding-top:10px;">
<h3>通过会话获取：</h3>
session_time : {$session_time}
</pre>

{include file="frame/foot.tpl"}