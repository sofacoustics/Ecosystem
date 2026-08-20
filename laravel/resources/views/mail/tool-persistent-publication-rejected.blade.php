<div>
	<x-mail.header :admin="$admin" :user="$tool->user" />
	@if($admin)
		<p>The persistent publication of the tool <x-mail.tool :tool="$tool" :admin="$admin" /> has been rejected by <x-mail.actor :actor="$actor" />. The user has been informed and is waiting for an admin to reach out!</p>
	@else
		<p>The persistent publication of your tool <x-mail.tool :tool="$tool" /> has been rejected. You will be contacted by the responsible person with an explanation and further instructions.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
