{include file="frame/head.tpl"}

<div style="display-inline:block;">
    <input type="button" value="创建单条记录" onclick="javascript:location.href='?act=1'" />
    <input type="button" value="批量创建记录" onclick="javascript:location.href='?act=2'" />
    <input type="button" value="事务成功测试" onclick="javascript:location.href='?act=3'" />
    <input type="button" value="事务失败测试" onclick="javascript:location.href='?act=4'" />
    <input type="button" value="事务嵌套测试" onclick="javascript:location.href='?act=5'" />
</div>

{include file="frame/error.tpl"}

{if $result}
<table class="tlist" >
    <tbody>
    {foreach $result as $item}
        <tr>
            <td>{$item.id}</td>
            <td>{$item.title}</td>
            <td>{if $item.dtime}{$item.dtime|date_format:'%Y-%m-%d %H:%M:%S'}{else}-{/if}</td>
        </tr>
    {/foreach}
    </tbody>
</table>
{/if}

{include file="frame/foot.tpl"}