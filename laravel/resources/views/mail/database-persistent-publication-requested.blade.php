<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The persistent publication of the database <x-mail.database :database="$database" :admin="$admin" /> has been requested by <x-mail.actor :actor="$actor" />. Please review it and approve or reject.</p>
	@else
		<p>Your publication upload has finished and your request for the persistent publication of the database <x-mail.database :database="$database" /> has been send to the {{ config('app.name') }} admins. You will be informed when the publication has been approved.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
