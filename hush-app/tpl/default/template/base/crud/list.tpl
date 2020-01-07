{include file="frame/head.tpl"}
{assign var=blocks value=","|explode:$blocks}
{assign var=hides value=","|explode:$hides}
{if $tpl_list_error}{include file=$tpl_list_error}{else}{include file="frame/error.tpl"}{/if}
{if $tpl_list_tabs}{include file=$tpl_list_tabs}{else}{include file="base/crud/list_tabs.tpl"}{/if}
{if $tpl_list_navi}{include file=$tpl_list_navi}{else}{include file="base/crud/list_navi.tpl"}{/if}
<div class="mainbox">
{if $tpl_list_search}{include file=$tpl_list_search}{else}{include file="base/crud/list_search.tpl"}{/if}
{if $tpl_list_table}{include file=$tpl_list_table}{else}{include file="base/crud/list_table.tpl"}{/if}
</div>
{include file="frame/datetimepicker.tpl"}
{include file="frame/window.tpl"}
{if !$_no_foot}
{include file="frame/foot.tpl"}
{/if}