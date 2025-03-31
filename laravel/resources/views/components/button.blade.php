{{--
	A general purpose button

	Parameters:

		method     	The form method.
		action 		??
		type	   	button|submit|reset  Default: 'submit' for 'action' buttons, 'button' otherwise

	Example:

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

@endphp
<div class="inline">
	@if ($attributes->has('action'))
		<form {{ $attributes }} method="{{ $formMethod }}">
			@if (in_array("$method", ['DELETE', 'PATCH', 'POST', 'PUT']))
				@csrf
				@if (in_array("$method", ['DELETE', 'PATCH', 'PUT']))
					@method($method)
				@endif
			@endif
	@endif
	<button {{ $attributes->except(['action', 'type', 'class']) }} type="{{ $type }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold mx-1 my-1 py-1 px-2 rounded">
		{{ $slot }}
	</button>
	@if ($attributes->has('action'))
		</form>
	@endif
</div>
