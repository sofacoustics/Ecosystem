@props([
	'database',
	'admin' => false,
])

<a href="{{ route('databases.show', $database->id) }}">
{{ $database->title }}
@if($admin)
	(id: {{ $database->id }})
@endif
</a>
