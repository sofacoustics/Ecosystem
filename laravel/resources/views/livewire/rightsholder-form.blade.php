@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@can('update', $database)
		@if ($rightsholder)
			<h3>Edit a rightsholder:</h3>
		@else
			<h3>Add a new rightsholder:</h3>
		@endif
				
				<p>Persons are to be entered with given and family names. Optionally, ORCiD ID and institutional affiliation can be added.<br>
					 Institutions do not have given and family names. They can be identified by an ID from the 
						<a href="https://ror.org" target="_blank">Research Organization Registry (ROR)</a> 
					list.
				</p>
				
		<form wire:submit.prevent="save">
			<div class="mb-4">
				<label for="rightsholderName" class="{{ $labelClass }}">Name: (*)</label>
				<input wire:model="rightsholderName" type="text" 
									placeholder="Name of the person or institution."
									id="rightsholderName" class="{{ $inputClass }}" required />
				@error('rightsholderName')
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
				<input wire:model="nameIdentifier" type="text" 
									placeholder="ROR identifier (if institution), ORCID (if person), free text (if other)."
									id="nameIdentifier" class="{{ $inputClass }}" />
				@error('nameIdentifier')
					<span class="text-red-500">{{ $message }}</span>
				@enderror
			</div>

			<div class="mb-4">
				<label for="schemeURI" class="{{ $labelClass }}">Scheme URI:</label>
				<input wire:model="schemeURI" type="text" 
									placeholder="If name identifier is Other, provide the URI."
									id="schemeURI"
				  class="{{ $inputClass }}" />
				@error('schemeURI')
					<span class="text-red-500">{{ $message }}</span>
				@enderror
			</div>

			<div class="mt-4">
				<button type="submit" class="{{ $buttonClass }}">
					{{ $rightsholder ? 'Update rightsholder' : 'Create rightsholder' }}
				</button>
			</div>

		</form>
	@endcan
</div>
