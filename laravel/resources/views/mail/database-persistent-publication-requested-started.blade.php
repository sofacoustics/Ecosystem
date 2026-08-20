<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The user <x-mail.actor :actor="$actor" /> has requested the persistent publication of the database <x-mail.database :database="$database" :admin="$admin" />. The upload to RADAR has started. They will be informed when the upload has finished.</p
	@else
		<p>Your request for the persistent publication of the database <x-mail.database :database="$database" /> has been received by our server and the upload to our backend has started. This may take anywhere between minutes and hours depending on the size of your database. You will informed when the upload has finished.</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
