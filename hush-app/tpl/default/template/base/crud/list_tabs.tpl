{if $bps.toptabs}
<div class="tab_nav">
{foreach $bps.toptabs as $toptab}{if $_page eq $toptab.link}{assign var="is_tab" value="1"}{/if}{/foreach}
    <ul>
    {foreach $bps.toptabs as $toptab}
        <li{if $_page eq $toptab.link || (!$is_tab && $toptab.default)} class="cur"{/if}><a href="{$toptab.link}">{$toptab.name}</a></li>
    {/foreach}
    </ul>
</div>
{else}
<div class="maintop">
    <img src="{$_root}img/icon_arrow_right.png" class="icon" /> {$title}列表
</div>
{/if}