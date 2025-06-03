{{--
<x-alert>Your text goes here</x-alert>

	Parameters:

		title	A title. Default: 'Error'
		type	Could be 'error', 'info', 'message', 'warning'. Default: error
		color   A background color. Default - defined by type
--}}
@props([
    'type' => 'error',
    'title' => 'Error!',
    'color' => 'red',
])
@php
	if($type == 'status')
	{
		$title = 'Status';
		$color = 'blue';
	}
	else if($type == 'success')
	{
		$title = 'Success';
		$color = 'green';
	}
	$bgcolor = "bg-$color-100";
    $bordercolor = "border-$color-400";
	$textcolor = "text-$color-700";
@endphp

<div class="{{ $bgcolor }} border $bordercolor $textcolor px-4 py-3 rounded relative">
{{--<div class="{{ $bgcolor }} border $bordercolor $textcolor px-4 py-3 rounded fixed top-4 right-4 space-y-2 z-50">--}}
    <strong class="font-bold">{{ $title }}</strong>
    <span class="block sm:inline">{{ $slot }}</span>
</div>
