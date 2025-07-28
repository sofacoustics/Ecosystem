<div>
	<p>Dear {{ $tool->user->name }},</p>
	<p>The persistent publication of your tool <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a> has been approved.</p>
	<x-mail.footer />
</div>
