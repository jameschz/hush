<div class="left">

	<div class="menu" id="menu">
	
	{foreach $appList as $topAppList}
		{foreach $topAppList.list as $groupList}
		<div class="items_top_{$topAppList.id}">
			<dl class="dl_items_{$topAppList@index + 1}_{$groupList@index + 1}">
			<dt>{$groupList.name}</dt>
			<dd>
				<ul>
					{foreach $groupList.list as $appItem}
					<li><a href="{if $appItem.path}{$appItem.path}{else}javascript:;{/if}" target="main">{$appItem.name}</a></li>
					{/foreach}
				</ul>
			</dd>
			</dl>
		</div><!-- Item End -->
		{/foreach}
	{/foreach}
	
	</div>

</div>
<!-- left end -->