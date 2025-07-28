<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>The DOI {{ $database->doi }} has been assigned to your database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>.</p>
	<x-mail.footer />
</div>
