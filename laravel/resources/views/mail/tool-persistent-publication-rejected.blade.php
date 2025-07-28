<div>
	<p>Dear {{ $tool->user->name }},</p>
	<p>The persistent publication of your tool <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a> has been rejected. You will be contacted by the responsible person with an explanation and further instructions.</p>
	<p>The SONICOM Ecosystem Team!</p>
</div>
