@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@if ($relatedidentifier) 
		<h3>Edit a relation:</h3>
	@else
		<h3>Add a new relation:</h3>
	@endif
	<form wire:submit.prevent="save">
		<div class="block">
					
			<label class="{{ $labelClass }}" for="relationtype">Relation Type:</label>
			<select wire:model.live="relationtype">
				<option value="">Select a relation...</option>
				@foreach(\App\Models\Database::relationList() as $r => $t)
						<option value="{{ $r }}">{{ $t->display }}</option>
				@endforeach
			</select>
			@error('relationtype')
				<span class="text-red-500">{{ $message }}</span>
			@enderror
			
			<input wire:model="name" type="text" 
				placeholder="The identifier, e.g., the DOI (if type is DOI) or an URL (if type is URL)."
				id="name"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
			@error('name') <span class="text-red-500">{{ $message }}</span> @enderror

			<label class="{{ $labelClass }}" for="relatedidentifiertype">Identifier Type:</label>
			<select wire:model.live="relatedidentifiertype">
				<option value="">Select the type of identifier...</option>
				@foreach(\App\Models\Database::relatedidentifierList() as $r => $t)
						<option value="{{ $r }}">{{ $t->display }}</option>
				@endforeach
			</select>
			@error('relatedidentifiertype') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mt-4">
			<button type="submit" class="{{ $buttonClass }}">
				{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
			</button>
		</div>

	</form>
</div>
