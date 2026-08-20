<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The persistent publication of the database <x-mail.database :database="$database" /> has failed.</p>
		<p>The following details were returned by the server:</p>
		<p>{{ $radar_details }}</p>
	@else
		<p>The persistent publication of your database <x-mail.database :database="$database" /> has failed.</p>
		<p>The following details were returned by the server:</p>
		<p>{{ $radar_details }}</p>
	@endif
	<x-mail.footer :admin="$admin" />
</div>
