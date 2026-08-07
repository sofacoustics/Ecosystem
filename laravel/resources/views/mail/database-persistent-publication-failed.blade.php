<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>The persistent publication of your database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a> has failed.</p>
  <p>The following details were returned by the server:</p>
  <p>{{ $radar_details }}</p>
	<x-mail.footer />
</div>
