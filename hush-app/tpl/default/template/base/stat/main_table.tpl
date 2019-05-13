{if $tables}
<table class="tlist">
	<thead>
		<tr class="title">
			{foreach $bps.table as $k => $v}
			<th align="left">{$v.name}</th>
			{/foreach}
		</tr>
	</thead>
	<tbody>
		{foreach $tables as $table}
		<tr>
			{foreach $bps.table as $k => $v}
			<td align="left">{$table[$k]}</td>
			{/foreach}
		</tr>
		{/foreach}
	</tbody>
    {if $totals}
	<tfoot>
		<tr style="background:#ffffe0">
			<td align="left">总计</td>
			{foreach $totals as $total}
			<td align="left">{$total}</td>
			{/foreach}
		</tr>
	</tfoot>
    {/if}
</table>
{/if}