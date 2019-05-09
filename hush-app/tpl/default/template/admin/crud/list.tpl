{include file="frame/head.tpl"}
{assign var=blocks value=","|explode:$blocks}
{assign var=hides value=","|explode:$hides}
{include file="admin/crud/list_tabs.tpl"}
{include file="frame/error.tpl"}
<div class="mainbox">
{include file="admin/crud/list_search.tpl"}
{include file="admin/crud/list_table.tpl"}
</div>
{include file="frame/datetimepicker.tpl"}
{include file="frame/window.tpl"}
{if !$_no_foot}
{include file="frame/foot.tpl"}
{/if}