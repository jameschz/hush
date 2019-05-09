{if $allroles}
<table>
	<tr>
		<td>
			<select multiple id="all_role" size="{$max_role_size}" style="width:139px;">
			{foreach $allroles as $option}
				<option value="{$option.id}">{$option.name} ({$option.alias})</option>
			{/foreach}
			</select>
		</td>
		<td>
			<input id="role_add" type="button" value=" &gt; " style="width:20px;" /><br/>
			<input id="role_del" type="button" value=" &lt; " style="width:20px;" />
		</td>
		<td>
			<input type="hidden" name="roles_" value="{$oldroles}" />
			<select multiple id="sel_role" name="roles[]" size="{$max_role_size}" style="width:139px;">
			{foreach $selroles as $option}
				{if $option.readonly}
					<optgroup label="{$option.name} *"></optgroup>
				{else}
					<option value="{$option.id}">{$option.name} ({$option.alias})</option>
				{/if}
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding:5px 0px 5px 0px">
			<img src="{$_root}img/icon_lock.png" class="icon" /> 标记为 * 的权限选项您是无权修改的！
		</td>
	</tr>
</table>
{else}
<img src="{$_root}img/icon_warn.png" class="icon" /> 您没有任何角色的选择权限！
{/if}

<script type="text/javascript">
// set add & remove options
$('#role_add').click(function(){
	$('#all_role option:selected').each(function(){
		if ($("#sel_role option:contains('"+$(this).text()+"')").length < 1) {
			$('#sel_role').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
		}
	});
	$('#all_role option:selected').each(function(){
		$(this).attr('selected', false);
	});
});
// remove select categories
$('#role_del').click(function(){
	$('#sel_role option:selected').each(function(){
		$(this).remove();
	});
});
// select all roles
$('form').submit(function(){
	$('#sel_role option').attr('selected', true);
	return true;
});
</script>