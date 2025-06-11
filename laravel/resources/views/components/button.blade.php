{{--
	A general purpose button

	Parameters:

		method     	The form method.
		action 		??
		type	   	button|submit|reset  Default: 'submit' for 'action' buttons, 'button' otherwise
		disabled    true or false. default: false
		loadingText If set to something, this text is used once the button has been pressed.

	Example:

			<x-button 
			<x-button
				method="DELETE"
				action="{{ route('datasets.destroy', [$dataset]) }}"
				class="inline">
				DELETE
			</x-button>

			<x-button>
				Create
			</x-button>

--}}
@props([
	'method' => 'POST',
	'disabled' => false,
	'loadingText' => '',
])
@php
	if ($method == 'GET') {
		$formMethod = 'GET';
	} else {
		$formMethod = 'POST';
	}
	// if this is a button in a form, use type=submit by default
	if ($attributes->has('action')) {
		$type = 'submit';
	} else {
		$type = 'button';
	}
	// if type is specified, then use this.
	if ($attributes->has('type')) {
		$type = $attributes->get('type');
	}

	// button colors: moved to AppServiceProvider (global)
	/*
	$enabledColors = 'blue-500 bg-blue-500 hover:bg-blue-700 text-white';
	$disabledColors = 'gray-400 bg-gray-400 text-white';
	$deleteColors = 'red-400 bg-red-400 hover:bg-red-600 text-white';
	*/
	$classColors = $buttonColorEnabled;
	if ($disabled) {
		$classColors = $buttonColorDisabled;
	}
	if ("$method" == 'DELETE') {
		$classColors = $buttonColorDelete;
	}

@endphp
<div 
	x-data="{ loading: false }"
	class="inline">
	@if ($attributes->has('action'))
		<form {{ $attributes }} method="{{ $formMethod }}"
			@submit="loading = true">
			@if (in_array("$method", ['DELETE', 'PATCH', 'POST', 'PUT']))
				@csrf
				@if (in_array("$method", ['DELETE', 'PATCH', 'PUT']))
					@method($method)
				@endif
			@endif
	@endif
	<button {{ $attributes->except(['action', 'type', 'class']) }} type="{{ $type }}"
		@if ($disabled) disabled @endif
		@if ($loadingText) x-text="loading ? '{{ $loadingText }}' : '{{ $slot }}'" @endif
		{{-- If we're not a form, then give user immediate feedback that it's been pressed
		@if (!$attributes->has('action')) x-on:click="loading = true" @endif--}}
		:disabled="loading"
		:class="loading ? '{{ $buttonColorDisabled }}' : '{{ $classColors }}'"
		class="{{ $classColors }} {{ $buttonStyle }}">
		{{ $slot }}
	</button>
	@if ($attributes->has('action'))
		</form>
	@endif
</div>
