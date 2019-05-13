{if $v.list}
<th align="left">
{if $view_orders.$k}
    <a href="{$view_orders[$k]['link']}">{$v.name}{if $view_orders[$k]['sign']}{$view_orders[$k]['sign']}{/if}</a>
{else}
    {$v.name}
{/if}
</th>
{/if}