{{--
	A button for use in a Livewire blade

	Call with, e.g.

		<x-livewire-button wire:click="save" loading="Saving...">
			Save
		</<x-livewire-button>

	Or for form submission (outside of Livewire):

		<form method="POST" action="/submit">
			@csrf
			<x-button>
				Submit
			</x-button>
		</form>
--}}

@props([
    'loading' => null, // Optional: loading text, e.g., "Saving..."
    'type' => 'submit', // Default to submit, can be overridden
	'style' => 'submit', // Default 'submit'. Possible values: submit, delete
])

@php
    // Determine the Livewire action (if any) for loading targeting
    $wireClick = $attributes->wire('click')->value();
	if($style == 'delete')
		$classColors = $buttonColorDelete;
	else
		$classColors = $buttonColorEnabled;
	$buttonClass = "$classColors $buttonStyle";
@endphp

<button
    {{ $attributes->merge(['type' => $type, 'class' => $buttonClass]) }}
    @if($wireClick) wire:loading.attr="disabled" @endif
	wire:loading.class.remove="{{ $classColors }}"
	wire:loading.class="{{ $buttonColorDisabled }}"
>
    @if ($loading && $wireClick)
        <span wire:loading.remove wire:target="{{ $wireClick }}">{{ $slot }}</span>
        <span wire:loading wire:target="{{ $wireClick }}">{{ $loading }}</span>
    @else
        {{ $slot }}
    @endif
</button>
