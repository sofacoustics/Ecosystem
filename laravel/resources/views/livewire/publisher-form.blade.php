@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-1/2 rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>
	@if ($publisher)
		<h3>Edit a publisher:</h3>
	@else
		@can('update', $publisherable)
			{{-- we need this later <small><x-livewire-button wire:click="copyFromCreators">Copy from Creators</x-livebutton-button></small> --}}
		@endcan
		<h3>Add a new publisher:</h3>
	@endif
			
			<p>Persons are to be entered with given and family names. Optionally, ORCiD ID and institutional affiliation can be added.<br>
				 Institutions do not have given and family names. They can be identified by an ID from the 
					<a href="https://ror.org" target="_blank">Research Organization Registry (ROR)</a> 
				list.
			</p>

	<form wire:submit.prevent="save">
		<div class="mb-4">
			<label for="publisherName" class="{{ $labelClass }}">Name: (*)</label>
			<input wire:model="publisherName" type="text" 
								placeholder="Name of the person or institution."
								id="publisherName" class="{{ $inputClass }}" required />
			<x-livewire-button type="button" wire:click="fillinmydata()">Fill in my data</x-livewire-button>
			@error('publisherName')
				<span class="text-red-500">{{ $message }}</span>
			@enderror
		</div>

		<div class="block">
			<label class="{{ $labelClass }}" for="nameIdentifierSchemeIndex">Name Identifier Scheme:</label>
			<select class="{{ $selectClass }}" id="nameIdentifierSchemeIndex" wire:model.live="nameIdentifierSchemeIndex">
				<option value="">None</option>
				<option value="2">{{ \App\Models\Creator::nameIdentifierScheme(2) }}</option>
				<option value="1">{{ \App\Models\Creator::nameIdentifierScheme(1) }}</option>
				<option value="0">{{ \App\Models\Creator::nameIdentifierScheme(0) }}</option>
			</select>
			@error('nameIdentifierSchemeIndex')
				<span class="text-red-500">{{ $message }}</span>
			@enderror
		</div>


		@if ($nameIdentifierSchemeIndex!="")
			<div class="mb-4">
				<label for="nameIdentifier" class="{{ $labelClass }}">Name Identifier:</label>
				@if ($nameIdentifierSchemeIndex==2)<!-- ROR -->
					<input wire:model="nameIdentifier" type="text" 
										placeholder="ROR identifier."
										id="nameIdentifier" class="{{ $inputClass }}" />
				@elseif ($nameIdentifierSchemeIndex==1)<!-- ORCID -->
					<input wire:model="nameIdentifier" type="text" 
										placeholder="ORCID."
										id="nameIdentifier" class="{{ $inputClass }}" />
				@else <!-- Other -->
					<input wire:model="nameIdentifier" type="text" 
										placeholder="Identifier as free text."
										id="nameIdentifier" class="{{ $inputClass }}" />
				@endif
				@error('nameIdentifier')
					<span class="text-red-500">{{ $message }}</span>
				@enderror
			</div>
			@if($nameIdentifierSchemeIndex==0)<!--OTHER-->
				<div class="mb-4">
					<label for="schemeURIother" class="{{ $labelClass }}">Scheme URI:</label>
					<input wire:model="schemeURIother" type="text" 
										placeholder="URI of the identifier."
										id="schemeURIother"
						class="{{ $inputClass }}" />
					@error('schemeURIother')
						<span class="text-red-500">{{ $message }}</span>
					@enderror
				</div>
			@endif
		@endif

		<div class="mt-4">
			<button type="submit" class="{{ $buttonClass }}">
				{{ $publisher ? 'Update Publisher' : 'Create Publisher' }}
			</button>
		</div>

	</form>
</div>
