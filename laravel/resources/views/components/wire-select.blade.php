<!--
    a component to use in a Livewire component without being a 'nested' Livewire component

    'attribute'     the Livewire component attribute to get data from / write data to
    'label'         the text to use with the <label>
    'model'         the model to extract values from
    'field'         the field in the model to extract values from
    'searchterm'    the search string to retrieve rows from the model with

-->
@props([
    'attribute',
    'label' => '',
    'values' => '',
    'model' => '',
    'field' => '',
    'searchterm' => ''
])

<div {{ $attributes->merge(['class' => 'w-full px-3 mb-6 md:mb-0']) }}>
    <label 
        id="{{ $attribute }}"
        class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-1"
    >{{ $label }}</label>
    <select 
        class="appearance-none block w-full bg-gray-10 text-gray-700 border border-gray-300 rounded py-3 px-4 leading-tight focus:outline-none focus:bg-white focus:border-gray-500"
        wire:model.change="{{ $attribute }}"
    >
        @if($model != '')
            @foreach($model::where("$field", "$searchterm")->get() as $o)
                <option value="{{ $o->value }}">{{ $o->display }}</option>
            @endforeach
        @endif
    </select>
</div>
