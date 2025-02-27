{{--
	Display a message with an optional timeout (will disappear after n milliseconds)

	Parameters:

		show		Set to a variable used to toggle the message with
		timeout		Set to a timeout in milliseconds after which the message will disappear.
--}}
@props([
    'timeout' => '',
    'show' => '',
])
@php
    $timeoutstring = '';
    if ($timeout != '') {
        $timeoutstring = ", $timeout";
    }
@endphp
<div {{ $attributes }}>
    @if ($show != '')
        <template x-if="{{ $show }}">
    @endif
    <div x-data="{ show: true }" x-show="show"
        @if ($timeoutstring != '') x-init="console.log('x-init x-message (timeoutstring={{ $timeoutstring }})'), setTimeout(() => show = false {{ $timeoutstring }})" @endif>
        {{ $slot }}
    </div>
    @if ($show != '')
        </template>
    @endif
</div>
