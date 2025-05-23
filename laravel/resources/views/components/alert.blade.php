{{--
<x-alert>Your text goes here</x-alert>

	Parameters:

		title	A title. Default: 'Error'
		type	Could be 'error', 'info', 'message', 'warning'. Default: error
		color   A background color. Default - defined by type
--}}
@props([
    'title' => 'Error!',
    'type' => 'error',
    'color' => 'red',
])
@php
	$bgcolor = "bg-$color-100";
    $bordercolor = "border-$color-400";
	$textcolor = "text-$color-700";
@endphp

<div class="{{ $bgcolor }} border $bordercolor $textcolor px-4 py-3 rounded relative">
    <strong class="font-bold">{{ $title }}</strong>
    <span class="block sm:inline">{{ $slot }}</span>
</div>
