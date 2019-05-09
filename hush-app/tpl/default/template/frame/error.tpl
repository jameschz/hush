{if $errors}
	<div class="errorbox">
	{foreach $errors as $error}
		<span><b>!</b> {$error}</span>
	{/foreach}
	</div>
{/if}
{if $msgs}
	<div class="errorbox">
	{foreach $msgs as $msg}
		<span><b>!</b> {$msg}</span>
	{/foreach}
	</div>
{/if}