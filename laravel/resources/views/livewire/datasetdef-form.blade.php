@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = "text-gray-700 mb-2 block font-bold";
    $selectClass = "form-control text-gray-700 rounded-lg mb-2 block font-bold";
    $inputClass = "text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none";
    $buttonClass = "bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white";
@endphp
<div>
    @auth
        @if (Auth::user()->id == $database->user_id)
            <p>
                @if($datasetdef)
                    Update this dataset definition.
                @else
                    Create a new dataset definition for the database "{{ $database->title }}"
                @endif
            </p>
            <form wire:submit.prevent="save">
                <div class="mb-4">
                    <label for="Name" class="{{ $labelClass }}">Name</label>
                    <input wire:model="name" type="text" id="name"
                        class="{{ $inputClass }}" required />
                    @error('name')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="block">
                    <label class="{{ $labelClass }}" for="datafiletype">Datafiletype</label>
                    <select class="{{ $selectClass }}" id="datafiletype" wire:model="datafiletype_id">
                        <option value="">Select a datafile type</option>
                        @foreach ($datafiletypes as $datafiletype)
                            <option value="{{ $datafiletype->id }}">{{ $datafiletype->name }}</option>
                        @endforeach
                    </select>
                    @error('datafiletype_id')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div>
                    <label class="{{ $labelClass }}" for="widget">Widget</label>
                    <select class="{{ $selectClass }}" id="widget" wire:model="widget_id">
                        <option value="">Select a widget</option>
                        @foreach ($widgets as $widget)
                            <option value="{{ $widget->id }}">{{ $widget->name }}</option>
                        @endforeach
                    </select>
                    @error('widget_id')
                        <span class="text-red-500">{{ $message }}</span>
                    @enderror
                </div>
                <div class="mt-4">
                    <button type="submit" class="{{ $buttonClass }}">
                        {{ $datasetdef ? 'Update Datasetdef' : 'Create Datasetdef' }}
                    </button>
                </div>
            </form>
        @endif
    @endauth
</div>
