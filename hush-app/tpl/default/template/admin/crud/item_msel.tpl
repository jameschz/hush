<table>
	<tr>
		<td>
			<select multiple id="msel_left_{$k}" style="width:139px;">
			{foreach $item._data._msel_l[$k] as $k1 => $v1}
				<option value="{$k1}">{$v1}</option>
			{/foreach}
			</select>
		</td>
		<td>
			<input id="msel_r2l_{$k}" type="button" value=" &gt; " style="width:20px;" /><br/>
			<input id="msel_l2s_{$k}" type="button" value=" &lt; " style="width:20px;" />
		</td>
		<td>
			<select multiple id="msel_right_{$k}" name="{$k}[]" style="width:139px;">
			{foreach $item._data._msel_r[$k] as $k2 => $v2}
				<option value="{$k2}">{$item._data._msel_l[$k][$k2]}</option>
			{/foreach}
			</select>
		</td>
	</tr>
	<tr>
		<td colspan="3" style="padding:5px 0px 5px 0px">
			<img src="{$_root}img/icon_warn.png" class="icon" /> 右侧是已经选中的内容！
		</td>
	</tr>
</table>

<script type="text/javascript">
$(function(){
	$('#msel_r2l_{$k}').click(function(){
		$('#msel_left_{$k} option:selected').each(function(){
			if ($("#msel_right_{$k} option:contains('"+$(this).text()+"')").length < 1) {
				$('#msel_right_{$k}').append("<option value='"+$(this).val()+"'>"+$(this).text()+"</option>");
			}
		});
		$('#msel_left_{$k} option:selected').each(function(){
			$(this).attr('selected', false);
		});
	});
	$('#msel_l2s_{$k}').click(function(){
		$('#msel_right_{$k} option:selected').each(function(){
			$(this).remove();
		});
	});
	$('input[type=submit]').click(function(){
		$('#msel_right_{$k} option').attr('selected', true);
	});
});
</script>