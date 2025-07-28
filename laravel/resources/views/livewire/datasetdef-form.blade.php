@php
    // https://laravel.com/docs/11.x/blade#conditional-classes
    $labelClass = 'text-gray-700 mb-2 block font-bold';
    $selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
    $inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
    $buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>

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
				<select class="{{ $selectClass }}" id="datafiletype" wire:model.live="datafiletype_id" disabled title="The type cannot be modified because of existing Datasets.">
			@else
				<select class="{{ $selectClass }}" id="datafiletype" wire:model.live="datafiletype_id" >
			@endif 
				<option value="" disabled>Select a datafile type</option>
				@foreach ($datafiletypes as $datafiletype)
					<option value="{{ $datafiletype->id }}">{{ $datafiletype->name }}</option>
				@endforeach
			</select>
			@if($datafiletype_id)
				<p>{{ \App\Models\Datafiletype::where('id', $datafiletype_id)->get()->first()->description }}</p>
			@endif
			@error('datafiletype_id')
				<span class="text-red-500">{{ $message }}</span>
			@enderror
		</div>
		<br>
		<div>
			<label class="{{ $labelClass }}" for="widget">Widget:</label>
			@if(count($database->datasets))
				<select class="{{ $selectClass }}" id="widget" wire:model.live="widget_id" disabled title="The widget cannot be modified because of existing Datasets.">
			@else
				<select class="{{ $selectClass }}" id="widget" wire:model.live="widget_id">
			@endif
				@foreach ($widgets as $widget)
					@if($widget->id == $widget_id)
						<option value="{{ $widget->id }}" wire:key="{{ $widget->id }}" selected>{{ $widget->name }}</option>
					@else
						<option value="{{ $widget->id }}" wire:key="{{ $widget->id }}" >{{ $widget->name }}</option>
					@endif
				@endforeach
			</select>
			@if($datafiletype_id)
				<p>{{ \App\Models\Widget::where('id', $widget_id)->get()->first()->description }}</p>
				@if(\App\Models\Widget::where('id', $widget_id)->get()->first()->service_id)
					<p>This widget uses the service <b>{{\App\Models\Widget::where('id', $widget_id)->get()->first()->service->name}}</b> to 
					'{{\App\Models\Widget::where('id', $widget_id)->get()->first()->service->description}}'</p>
				@endif
			@endif
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
</div>
