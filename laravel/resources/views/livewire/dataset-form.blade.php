{{--
    Create or modify a dataset using the DatasetForm livewire component
--}}
@php
    $labelClass = "text-gray-700 mb-2 block font-bold";
    $selectClass = "form-control text-gray-700 rounded-lg mb-2 block font-bold";
    $inputClass = "text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none";
    $buttonClass = "bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white";
@endphp
<div>
    <form wire:submit.prevent="save">
        <div class="mb-4">
            <label for="Name" class="{{ $labelClass }}">Name</label>
            <input wire:model="name" type="text" id="name"
                class="{{ $inputClass }}" required />
            @error('name')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="mb-4">
            <label for="Name" class="{{ $labelClass }}">Description</label>
            <input wire:model="description" type="text" id="description"
                class="{{ $inputClass }}"/>
            @error('description')
                <span class="text-red-500">{{ $message }}</span>
            @enderror
        </div>
        <div class="mt-4">
            <button type="submit" class="{{ $buttonClass }}">
                {{ $dataset ? 'Update Dataset' : 'Create Dataset' }}
            </button>
        </div>
    </form>

    @env('local')
        <p>resource/views/livewire/dataset-form.blade.php</p>
    @endenv
</div>
