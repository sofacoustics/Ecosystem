<div>
	<p>Dear {{ $tool->user->name }},</p>
	<p>A persistent publication has been requested for the tool <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a>.</p>
	<x-mail.footer />
</div>
