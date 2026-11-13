<div>
	<x-mail.header :admin="$admin" :user="$database->user" />
	@if($admin)
		<p>The persistent publication of the database <x-mail.database :database="$database" /> has failed.</p>
		<p>The following details were returned by the server:</p>
		@if(filled($exceptionReference))
				<p>Exception Reference: {{ $exceptionReference }}</p>
		@endif
		<p>{{ $radar_details }}</p>
	@else
		<p>The persistent publication of your database <x-mail.database :database="$database" /> has failed.</p>
		@if(filled($exceptionReference))
				<p>Please quote this reference when contacting us: <strong>{{ $exceptionReference }}</strong></p>
		@endif
	@endif
	<x-mail.footer :admin="$admin" />
</div>
