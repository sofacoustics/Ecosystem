<!-- A general purpose button -->
@props([
    'type' => 'button',
])
@php
	$buttonClass = "$buttonColorEnabled $buttonStyle";
@endphp
<input {{ $attributes->merge(['class' => $buttonClass]) }}
    type="{{ $type }}"
    value="{{ $slot }}">
