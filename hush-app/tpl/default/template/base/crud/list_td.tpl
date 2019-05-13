{if $v.list}
<td align="left" style="{if $item._style}{$item._style}{else}{if $v.style}{$v.style}{/if}{/if}">
{include file="base/crud/item.tpl" from=list}
</td>
{/if}