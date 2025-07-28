<div>
	<p>Dear {{ $tool->user->name }},</p>
	<p>The DOI {{ $tool->doi }} has been assigned to your tool <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a>.</p>
	<x-mail.footer />
</div>
