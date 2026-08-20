<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The persistent publication of the database <x-mail.database :database="$database" :admin="$admin" /> has been rejected by <x-mail.actor :actor="$actor" />. The database owner has been informed and is waiting for an admin to reach out!</p>
	@else
		<p>The persistent publication of your database <x-mail.database :database="$database" /> has been rejected. You will be contacted by the responsible person with an explanation and further instructions.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
