@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = 'text-gray-700 mb-2 block font-bold';
    $selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
    $inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@auth
		@if (Auth::user()->id == $database->user_id)
			<p>
				@if ($datasetdef)
					<h3>Edit the datafile definition:</h3>
				@else
					<h3>Add a new datafile definition:</h3>
				@endif
			</p>

			<form wire:submit.prevent="save">
				<div class="mb-4">
					<label for="Name" class="{{ $labelClass }}">Name (*):</label>
					<input wire:model="name" type="text" id="name" class="{{ $inputClass }}" required />
					@error('name')
						<span class="text-red-500">{{ $message }}</span>
					@enderror
				</div>
				
				<div class="mb-4">
					<label for="Description" class="{{ $labelClass }}">Description:</label>
					<input wire:model="description" type="text" id="description" class="{{ $inputClass }}" />
					@error('description')
						<span class="text-red-500">{{ $message }}</span>
					@enderror
				</div>

				<div class="block">
					<label class="{{ $labelClass }}" for="datafiletype">Type:</label>
					@if(count($database->datasets))
						<select class="{{ $selectClass }}" id="datafiletype" wire:model="datafiletype_id" disabled title="The type cannot be modified because of existing Datasets.">
					@else
						<select class="{{ $selectClass }}" id="datafiletype" wire:model="datafiletype_id" >
					@endif 
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
					<label class="{{ $labelClass }}" for="widget">Linked Widget:</label>
					@if(count($database->datasets))
						<select class="{{ $selectClass }}" id="widget" wire:model="widget_id" disabled title="The widget cannot be modified because of existing Datasets.">
					@else
						<select class="{{ $selectClass }}" id="widget" wire:model="widget_id">
					@endif
						<option value="" disabled>Select a widget</option>
						@foreach ($widgets as $widget)
							<option value="{{ $widget->id }}">{{ $widget->name }} </option>
						@endforeach
					</select>
					@error('widget_id')
						<span class="text-red-500">{{ $message }}</span>
					@enderror
				</div>
				<div class="mt-4">
					<x-button type="submit">
						{{ $datasetdef ? 'Update' : 'Add' }}
					</x-button>
				</div>
			</form>
		@endif
	@endauth
	<!-- <p>resources\views\livewire\datasetdef-form.blade.php</p> --!>
</div>
