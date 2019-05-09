{if $_submit_once}
    {if !$msgs}
    <input type="submit" value="确认保存" onclick="javascript:$.overlay.show();" />
    {else}
    <input type="button" value="关闭并返回" onclick="javascript:parent.close();" />
    {/if}
{else}
    <input type="submit" value="确认保存" onclick="javascript:$.overlay.show();" />
{/if}
{if $smarty.get._back}
&nbsp;<input type="button" value="返回上级" onclick="javascript:location.href='{$smarty.get._back}';" />
{/if}