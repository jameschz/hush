{include file="frame/head.tpl"}
{assign var=blocks value=","|explode:$blocks}

<div class="maintop">
<img src="{$_root}img/icon_arrow_right.png" class="icon" /> {$title}统计
</div>
{include file="frame/error.tpl"}
<div class="mainbox">
{include file="admin/stat/main_search.tpl"}
{include file="admin/stat/main_chart.tpl" width="300" height="400"}
{include file="admin/stat/main_table.tpl"}
</div>

{include file="frame/datetimepicker.tpl"}
{include file="frame/window.tpl"}
{include file="frame/foot.tpl"}