{if $errors}
	<div class="errorbox">
	{foreach $errors as $error}
		<span><b>!</b> {$error}</span>
	{/foreach}
	</div>
{/if}