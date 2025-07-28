<div>
	<p>Dear {{ $database->user->name }},</p>
	<p>The persistent publication of your database <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a> has been rejected. You will be contacted by the responsible person with an explanation and further instructions.</p>
	<p>The SONICOM Ecosystem Team!</p>
</div>
