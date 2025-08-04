@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@if ($keyword) 
		<h3>Edit a keyword:</h3>
	@else
		<h3>Add a new keyword:</h3>
	@endif
			
			<p>The keywords should differ from the title or the subtitle.<br>
				To ensure discoverability of the data, unique terms in English or standardized entries should be used.
			</p>
			
	<form wire:submit.prevent="save">
		<div class="block">
			<input wire:model="keywordName" type="text" 
				placeholder="Keyword in English."
				id="keywordName" required
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
			@error('keywordName') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mt-4">
			<x-button type="submit" class="{{ $buttonClass }}">
				{{ $keyword ? 'Update keyword' : 'Create keyword' }}
			</x-button>
		</div>

	</form>
</div>
