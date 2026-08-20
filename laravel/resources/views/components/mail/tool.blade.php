@props([
	'tool',
	'admin' => false,
])

<a href="{{ route('tools.show', $tool->id) }}">
{{ $tool->title }}
@if($admin)
	(id: {{ $tool->id }})
@endif
</a>
