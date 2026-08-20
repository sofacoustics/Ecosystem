<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The persistent publication of the database <x-mail.database :database="$database" :admin="$admin" /> has been approved by <x-mail.actor :actor="$actor" /></p>
	@else
		<p>The persistent publication of your database <x-mail.database :database="$database" /> has been approved.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
