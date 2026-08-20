<div>
	<x-mail.header :admin="$admin" :user="$tool->user" />
	@if($admin)
		<p>The persistent publication of the tool <x-mail.tool :tool="$tool" :admin="$admin" /> was requested by <x-mail.actor :actor="$actor" />.</p>
		<p>Please review it and approve or reject.</p>
	@else
		<p>Your request for the persistent publication of the tool <x-mail.tool :tool="$tool" /> has been sent to the {{ config('app.name') }} admins. You will be informed by email if the request is accepted or rejected.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
