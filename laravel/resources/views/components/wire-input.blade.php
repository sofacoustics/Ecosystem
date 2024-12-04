@props([
    'attribute',
    'label' => '',
    'type' => 'text',
    'placeholder' => ''
])

<div {{ $attributes }}>
	<label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="{{ $attribute }}">{{ $label }}</label>
	<input id="{{ $attribute }}" wire:model="{{ $attribute }}" type="{{ $type }}" placeholder="{{ $placeholder }}" class="appearance-none block w-full bg-gray-200 text-gray-700 border border-gray-200 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"/>
	<x-fielderror attribute="{{ $attribute }}" />
</div>
