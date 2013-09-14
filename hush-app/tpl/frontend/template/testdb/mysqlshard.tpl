{include file="frame/head.tpl"}

{literal}<style>
table.resultbox {margin:10px;}
table.resultbox td {padding:3px;}
table.resultbox td.cluster {background:#888; color:#fff; font-weight:bold}
table.resultbox td.product {background:#eee;}
</style>{/literal}

<div>

	<div style="margin:10px">
		<b>CRUD Sharding Demo : <font color=red>(NEW)</font></b>
	</div>
	
	<hr/>
	
	<form method="post" id="crud_form">
	<input type="hidden" name="act" value="" />
	<table style="margin:10px">
		<tr><td>ID</td><td><input type="text" name="pid" value="{if $smarty.post.pid}{$smarty.post.pid}{else}1{/if}" /> (shard id)</td></tr>
		<tr><td>Name</td><td><input type="text" name="pname" value="{if $smarty.post.pname}{$smarty.post.pname}{else}Test Product 1{/if}" /></td></tr>
		<tr><td>Desc</td><td><input type="text" name="pdesc" value="{if $smarty.post.pdesc}{$smarty.post.pdesc}{else}Test Product 1{/if}" /></td></tr>
		<tr><td colspan=2 style="padding-top:10px">
		<input type="button" value="Create" class="submit" />&nbsp;
		<input type="button" value="Read" class="submit" />&nbsp;
		<input type="button" value="Update" class="submit" />&nbsp;
		<input type="button" value="Delete" class="submit" />&nbsp;
		</td></tr>
	</table>
	</form>

	<div style="margin:10px;font-weight:bold">
		Action : <font color=blue>({if $act}{$act}{else}NULL{/if})</font>
	</div>
	
	<div style="margin:10px;font-weight:bold">
		Action Result : <font color=green>({if $res}{$res}{else}NULL{/if})</font>
	</div>
	
	<hr/>
	
	<div style="margin:10px;font-weight:bold">
		Realtime result :
	</div>

	<table class="resultbox" border="1">
		<tr>
			<td class="cluster" colspan="4">cluster_0</td>
		</tr>
		<tr>
			<td class="product" colspan="4">product_0</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Desc</td>
			<td>Time</td>
		</tr>
		{foreach key=key item=row from=$product_all_0}
		<tr {if $row.id == $sid}style="background:#ffffe0"{/if}>
			<td>{$row.id}</td>
			<td>{$row.name}</td>
			<td>{$row.desc}</td>
			<td>{$row.updatetime}</td>
		</tr>
		{/foreach}
		<tr>
			<td class="product" colspan="4">product_2</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Desc</td>
			<td>Time</td>
		</tr>
		{foreach key=key item=row from=$product_all_2}
		<tr {if $row.id == $sid}style="background:#ffffe0"{/if}>
			<td>{$row.id}</td>
			<td>{$row.name}</td>
			<td>{$row.desc}</td>
			<td>{$row.updatetime}</td>
		</tr>
		{/foreach}
		<tr>
			<td class="cluster" colspan="4">cluster_1</td>
		</tr>
		<tr>
			<td class="product" colspan="4">product_1</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Desc</td>
			<td>Time</td>
		</tr>
		{foreach key=key item=row from=$product_all_1}
		<tr {if $row.id == $sid}style="background:#ffffe0"{/if}>
			<td>{$row.id}</td>
			<td>{$row.name}</td>
			<td>{$row.desc}</td>
			<td>{$row.updatetime}</td>
		</tr>
		{/foreach}
		<tr>
			<td class="product" colspan="4">product_3</td>
		</tr>
		<tr>
			<td>ID</td>
			<td>Name</td>
			<td>Desc</td>
			<td>Time</td>
		</tr>
		{foreach key=key item=row from=$product_all_3}
		<tr {if $row.id == $sid}style="background:#ffffe0"{/if}>
			<td>{$row.id}</td>
			<td>{$row.name}</td>
			<td>{$row.desc}</td>
			<td>{$row.updatetime}</td>
		</tr>
		{/foreach}
	</table>

</div>

{literal}<script type="text/javascript">
$('input.submit').click(function(){
	$('input[name=act]').val($(this).val());
	$('#crud_form').submit();
});
</script>{/literal}

{include file="frame/foot.tpl"}