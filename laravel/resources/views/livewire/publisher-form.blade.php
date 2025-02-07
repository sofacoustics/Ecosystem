@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = 'text-gray-700 mb-2 block font-bold';
    $selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
    $inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
    @can('update', $database)
        @if ($publisher)
            <h3>Edit a publisher:</h3>
        @else
            <h3>Add a new publisher:</h3>
        @endif
        <form wire:submit.prevent="save">
            <div class="mb-4">
                <label for="publisherName" class="{{ $labelClass }}">Name: (*)</label>
                <input wire:model="publisherName" type="text" id="publisherName" class="{{ $inputClass }}" required />
                @error('publisherName')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="block">
                <label class="{{ $labelClass }}" for="nameIdentifierSchemeIndex">Name Identifier Scheme:</label>
                <select class="{{ $selectClass }}" id="nameIdentifierSchemeIndex" wire:model="nameIdentifierSchemeIndex">
                    <option value="">Select an identifier scheme</option>
                    <option value="0">{{ \App\Models\Creator::nameIdentifierScheme(0) }}</option>
                    <option value="1">{{ \App\Models\Creator::nameIdentifierScheme(1) }}</option>
                    <option value="2">{{ \App\Models\Creator::nameIdentifierScheme(2) }}</option>
                </select>
                @error('nameIdentifierSchemeIndex')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="nameIdentifier" class="{{ $labelClass }}">Name Identifier:</label>
                <input wire:model="nameIdentifier" type="text" id="nameIdentifier" class="{{ $inputClass }}" />
                @error('nameIdentifier')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mb-4">
                <label for="schemeURI" class="{{ $labelClass }}">Scheme URI:</label>
                <input wire:model="schemeURI" type="text" id="schemeURI"
                    class="{{ $inputClass }}" />
                @error('schemeURI')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="{{ $buttonClass }}">
                    {{ $publisher ? 'Update Publisher' : 'Create Publisher' }}
                </button>
            </div>

        </form>
    @endcan
</div>
