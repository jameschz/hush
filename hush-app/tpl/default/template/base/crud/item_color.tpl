{if $from eq 'add'}
	{foreach $item._data.$k as $k1 => $v1}
		{if $v1.type eq 'text'}
		<input type="text" class="colorbox" id="ax_{$k}_{$k1}" name="{$k}[{$k1}]" value="{$v1.value}" style="background:#{$v1.value}" placeholder="{$v1.name}" />
		{/if}
	{/foreach}
{elseif $from eq 'edit'}
	{foreach $item._data.$k as $k1 => $v1}
		{if $v1.type eq 'text'}
		<input type="text" class="colorbox" id="ax_{$k}_{$k1}" name="{$k}[{$k1}]" value="{$v1.value}" style="background:#{$v1.value}" placeholder="{$v1.name}" />
		{/if}
	{/foreach}
{else}
	{foreach $item._data.$k as $k1 => $v1}
		{if $v1.type eq 'text'}
		<input type="text" class="colorbox" id="ax_{$k}_{$k1}" name="{$k}[{$k1}]" value="{$v1.value}" style="background:#{$v1.value}" placeholder="{$v1.name}" />
		{/if}
	{/foreach}
{/if}