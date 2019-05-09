{include file="frame/head.tpl"}

<div style="display-inline:block;">
    <input type="button" value="增加最新日志" id="set_log_btn" />
</div>
<script>
$(function(){
    $('#set_log_btn').click(function(){
        location.href='/demo/code4?act=1'
    });
});
</script>

{include file="frame/error.tpl"}

<pre style="display-inline:block;padding-top:10px;">
<h3>日志最新信息：</h3>
{if $result}{foreach $result as $line}{$line}{/foreach}{else}NULL{/if}
</pre>

{include file="frame/foot.tpl"}