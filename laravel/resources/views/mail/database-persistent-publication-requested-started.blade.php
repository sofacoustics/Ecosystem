<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>Your request for the persistent publication of the database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a> has been recieved by our server and the upload to our backend has started. This may take anywhere between minutes and hours depending on the size of your database. You will informed when the upload has finished.</p>
	<x-mail.footer />
</div>
