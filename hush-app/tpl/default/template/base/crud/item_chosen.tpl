<link href="/app/chosen/chosen.min.css" rel="stylesheet" type="text/css" />
<script src="/app/chosen/chosen.min.js" type="text/javascript"></script>
<select class="common chosen-select" id="ax_{$k}" name="{$k}[]" multiple>
{foreach $v.data as $k1 => $v1}
    <option value="{$k1}" {if is_array($item[$k]) && $k1|in_array:$item[$k]}selected{/if}>{$v1}</option>
{/foreach}
</select>
<script type="text/javascript">
$('#ax_{$k}').chosen({
    'max_selected_options' : {if $v.chosen.max_num}{$v.chosen.max_num}{else}3{/if},
    'placeholder_text_multiple' : "{if $v.chosen.placeholder}{$v.chosen.placeholder}{else}请选择相关标签{/if}"
});
</script>