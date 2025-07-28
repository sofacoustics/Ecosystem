<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>The persistent publication of your database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a> has been approved.</p>
	<x-mail.footer />
</div>
