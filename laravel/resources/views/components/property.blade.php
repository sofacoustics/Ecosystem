{{--
 Component to output a property in the format

			name: description

	Parameters:

		name	The title to appear before the colon
		slot    The description to appear after the colon
--}}

@props([
	'name' => null,
	'slot' => null,
	'title' => null, // Hover text
])

@if($title)
	<div title="{{ $title }}">
		<b>{{ $name }}:</b> {{ $slot }}
	</div>
@else
	<div>
		<b>{{ $name }}:</b> {{ $slot }}
	</div>
@endif