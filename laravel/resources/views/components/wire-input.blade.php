@props([
    'attribute',
    'label' => '',
    'type' => 'text',
    'placeholder' => '',
    'readonly' => false
])

<div {{ $attributes->merge(['class' => 'w-full px-3 mb-6 md:mb-0']) }}>
	<label
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
        for="{{ $attribute }}"
        >{{ $label }}
    </label>
	<input
        @if($readonly)
            readonly
            class="focus:ring-offset-0 focus:ring-0 focus:border-none border-none bg-gray-100 rounded py-3 px-4"
        @else
            class="appearance-none block w-full bg-gray-10 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        @endif
        id="{{ $attribute }}"
        wire:model="{{ $attribute }}"
        type="{{ $type }}"
        placeholder="{{ $placeholder }}"
    />
	<x-fielderror attribute="{{ $attribute }}" />
</div>
