{if $opt eq 'app'}
	<option value="0">-- 请选择所属菜单 --</option>
	{foreach $appopts as $menus}
		<optgroup label="{$menus.name}" title="">
			{html_options options=$menus.list selected=$sel}
		</optgroup>
	{/foreach}
{elseif $opt eq 'menu'}
	<option value="0">-- 顶级菜单 --</option>
	{html_options options=$appopts selected=$sel}
{/if}