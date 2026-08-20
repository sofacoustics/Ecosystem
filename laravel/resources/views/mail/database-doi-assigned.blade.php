<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>A DOI has been assigned to the database <x-mail.database :database="$database" :admin="$admin" /> as requested by <x-mail.actor :actor="$actor" />.</p>
	@else
	<p>The DOI {{ $database->doi }} has been assigned to your database <x-mail.database :database="$database" />.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
