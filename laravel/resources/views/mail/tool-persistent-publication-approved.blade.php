<div>
	<x-mail.header :admin="$admin" :user="$tool->user" />
	@if($admin)
		<p>The persistent publication of the tool <x-mail.tool :tool="$tool" :admin="$admin" /> has been approved by <x-mail.actor :actor="$actor" />.</p>
	@else
		<p>The persistent publication of your tool <x-mail.tool :tool="$tool" /> has been approved.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
