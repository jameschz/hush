{* prepare vars *}
{if $item[$k] || $item[$k] eq 0}
{assign var="item_val" value="{$item[$k]}"}
{elseif $v.default !== null}
{assign var="item_val" value="{$v.default}"}
{/if}
{* add,edit item *}
{if $from eq 'add' || $from eq 'edit'}
	{* prepare vars *}
	{if $smarty.get.$k}
	{assign var="item_val" value="{$smarty.get.$k}"}
	{elseif $smarty.post.$k}
	{assign var="item_val" value="{$smarty.post.$k}"}
	{/if}
	{* main logic *}
	{if $v.find}
		<input type="text" class="common {$v.find}" id="ax_{$k}" name="{$k}" value="{$item_val}" readonly=true />
		{include file="base/find/{$v.find}.tpl" aw="10" ah="10"}
	{elseif $v.msel}
		{include file="base/crud/item_msel.tpl" from=$from}
	{elseif $v.files}
		{include file="base/crud/item_file.tpl" from=$from}
	{elseif $v.colors}
		{include file="base/crud/item_color.tpl" from=$from}
	{elseif $v.model}
		{if $item._data[$k]}
			<select name="{$k}" class="common" id="ax_{$k}">
			{foreach $item._data[$k] as $k1 => $v1}
				<option value="{$k1}" {if $item_val eq $k1}selected{/if}>{$v1}</option>
			{/foreach}
			</select>
		{else}
			<font color=red>请先添加{$v.name}！</font>
		{/if}
	{else}
		{if $v.type eq 'text'}
		<input type="text" class="common" id="ax_{$k}" name="{$k}" value="{$item_val}" />
		{elseif $v.type eq 'pass'}
		<input type="password" class="common" id="ax_{$k}" name="{$k}" value="" autocomplete="off" />
		{elseif $v.type eq 'date'}
		<input type="text" class="datepicker" id="ax_{$k}" name="{$k}" value="{if $item_val}{$item_val|date_format:'%Y-%m-%d'}{else}{$smarty.now|date_format:'%Y-%m-%d'}{/if}" />
		{elseif $v.type eq 'time'}
		<input type="text" class="datetimepicker" id="ax_{$k}" name="{$k}" value="{if $item_val}{$item_val|date_format:'%Y-%m-%d %H:%M:%S'}{else}{$smarty.now|date_format:'%Y-%m-%d %H:%M:%S'}{/if}" />
		{elseif $v.type eq 'textarea'}
		<textarea class="common" id="ax_{$k}" name="{$k}">{$item_val}</textarea>
		{elseif $v.type eq 'richtext'}
		<textarea class="richtext" id="ax_{$k}" name="{$k}">{$item_val}</textarea>
        {elseif $v.type eq 'flattext'}
        <textarea class="flattext" id="ax_{$k}" name="{$k}">{$item_val}</textarea>
        {elseif $v.type eq 'storytext'}
        <textarea class="storytext autosave" id="ax_{$k}" name="{$k}">{$item_val}</textarea>
		{elseif $v.type eq 'markdown'}
		<textarea class="common" id="ax_{$k}" name="{$k}" style="width:500px;height:300px;">{$item_val}</textarea>
        {elseif $v.type eq 'radio'}
        {foreach $v.data as $k1 => $v1}
        <input type="radio" id="ax_{$k}_{$k1}" name="{$k}" value="{$k1}" {if $item_val eq $k1}checked{/if} /> {$v1}
        {/foreach}
        {elseif $v.type eq 'checkbox'}
        {foreach $v.data as $k1 => $v1}
        <input type="checkbox" id="ax_{$k}_{$k1}" name="{$k}[]" value="{$k1}" {if $item[$k] && $k1|in_array:$item[$k]}checked{/if} /> {$v1}
        {/foreach}
		{elseif $v.type eq 'select'}
		<select class="common" id="ax_{$k}" name="{$k}">
		{foreach $v.data as $k1 => $v1}
			<option value="{$k1}" {if $item_val eq $k1}selected{/if}>{$v1}</option>
		{/foreach}
		</select>
        {elseif $v.type eq 'select-m'}
        <select class="common chosen-select" id="ax_{$k}" name="{$k}[]" multiple>
        {foreach $v.data as $k1 => $v1}
            <option value="{$k1}" {if is_array($item[$k]) && $k1|in_array:$item[$k]}selected{/if}>{$v1}</option>
        {/foreach}
        </select>
		{else}{* include types : html *}
		<input type="text" class="common" id="ax_{$k}" name="{$k}" value="{$item_val}" />
		{/if}
	{/if}
	<div style="display:block;">
	{if $from eq 'add' and $v.add_desc}<div class="item_desc">{$v.add_desc}</div>{/if}
	{if $from eq 'edit' and $v.edit_desc}<div class="item_desc">{$v.edit_desc}</div>{/if}
	</div>
{else}
	{* display logic *}
	{if $v.files}
		{foreach $item._data.$k as $k1 => $v1}
			{if $v.files.$k1.list}
				{if $v.files.$k1.type eq 'image' || $v.files.$k1.type eq 'imagecrop'}
				<img src="{if $v1.value}{$v1.value}{else}/img/no_image.png{/if}" class="icon_image icon_m" />
				{elseif $v.files.$k1.type eq 'audio'}
				<img src="{if $v1.value}/img/upload_audio.png{else}/img/no_image.png{/if}" data-src="{$v1.value}" class="{if $v1.value}icon_audio {/if}icon_m" />
				{elseif $v.files.$k1.type eq 'video'}
				<img src="{if $v1.value}/img/upload_video.png{else}/img/no_image.png{/if}" data-src="{$v1.value}" class="{if $v1.value}icon_video {/if}icon_m" />
				{else}
				<img src="{if $v1.value}/img/upload_image.png{else}/img/no_image.png{/if}" data-src="{$v1.value}" class="icon_m" />
				{/if}
			{/if}
		{/foreach}
	{elseif $v.colors}
		{foreach $item._data.$k as $k1 => $v1}
			{if $v.colors.$k1.list} 
			<div style="display:inline-block;width:20px;height:20px;border:solid 1px #000;background:#{$v1.value};"></div>
			{/if}
		{/foreach}
	{elseif $v.model}
		{if $item._data[$k][$item_val]}{$item._data[$k][$item_val]}{else}-{/if}
	{else}
		{if $v.type eq 'date'}
			{if $item_val}{$item_val|date_format:"%Y-%m-%d"}{else}-{/if}
		{elseif $v.type eq 'time'}
			{if $item_val}{$item_val|date_format:"%Y-%m-%d %H:%M:%S"}{else}-{/if}
		{elseif $v.type eq 'select' || $v.type eq 'radio'}
			{$v.data[$item_val]}
		{elseif $v.type eq 'text' || $v.type eq 'richtext'}
			{if $item_val}{$item_val|escape:"html"}{else}-{/if}
		{else}{* include types : html *}
			{if $item_val}{$item_val}{else}-{/if}
		{/if}
	{/if}
{/if}