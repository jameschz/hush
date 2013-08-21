{if $paging}
<div class="pagelist" style="float:right">
	<span>共 {$paging.totalPage} 页 / {$paging.totalNum} 条记录 </span><span class="indexPage">{$paging.prevStr}</span>{$paging.pageStr}<span class="nextPage">{$paging.nextStr}</span> 
</div>
{/if}