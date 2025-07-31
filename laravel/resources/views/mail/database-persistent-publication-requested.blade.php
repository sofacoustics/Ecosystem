<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>Your publication upload has finished and your request for the persistent publication of the database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a> has been send to the SONICOM Ecosystem admins. You will informed when the publication has been approved.</p>
	<x-mail.footer />
</div>
