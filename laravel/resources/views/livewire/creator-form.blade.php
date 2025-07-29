@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 block font-bold';
	$selectClass = 'form-control text-gray-700 rounded-lg mb-2 block font-bold';
	$inputClass = 'text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
@endphp
<div>

		@if ($creator)
			<h3>Edit a creator:</h3>
		@else
			<h3>Add a new creator:</h3>
		@endif
				
				<p>Persons are to be entered with given and family names. Optionally, ORCiD ID and institutional affiliation can be added.<br>
					 Institutions do not have given and family names. They can be identified by an ID from the 
						<a href="https://ror.org" target="_blank">Research Organization Registry (ROR)</a> 
					list.
				</p>

		<form wire:submit.prevent="save">
		
		<fieldset>
			<div class="py-4">
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				<input class="p-2" type="radio" id="person" name="type" value="person" wire:model.live="option"/>
				<label for="person">&nbsp; Person and affiliation</label>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				<input class="p-2" type="radio" id="institution" name="type" value="institution" wire:model.live="option"/>
				<label for="institution">&nbsp; Institution</label>
			</div>

			<table class="w-full">

				@if($option==="person")

					<tr>
						<td class="px-4"><label for="familyName" class="{{ $labelClass }}">Family Name:</label>
						<td class="px-4"><label for="givenName" class="{{ $labelClass }}">Given Name:</label>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="familyName" type="text" 
												placeholder="Family (last) name of the person. Required."
												id="familyName" class="{{ $inputClass }}" required/>
							@error('familyName')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
						<td class="px-4"><div class="mb-4">
							<input wire:model="givenName" type="text" 
												placeholder="Optional given (first) name of the person."
												id="givenName" class="{{ $inputClass }}" />
							@error('givenName')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
					</tr>
					<tr>
						<td class="px-4"><label for="nameIdentifier" class="{{ $labelClass }}">ORCID:</label></td>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="nameIdentifier" type="text" 
											placeholder="ORCID of the creator (without https://orcid.org/)"
											id="nameIdentifier" class="{{ $inputClass }}" />
						</div></td>
						<td class="px-4"><div class="mb-4">
							<x-livewire-button type="button" wire:click="fillinmydata">Fill in my data</x-livewire-button>
						</div></td>
					</tr>
					@error('nameIdentifier')
						<tr><span class="text-red-500">{{ $message }}</span></tr>
					@enderror
					<tr>
						<td class="px-4"><label for="creatorAffiliation" class="{{ $labelClass }}">Affiliation:</label></td>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="creatorAffiliation" type="text" 
													placeholder="Optional affiliation of the creator."
													id="creatorAffiliation"
								class="{{ $inputClass }}" />
							@error('creatorAffiliation')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
						<td></td>
					</tr>
					<tr>
						<td class="px-4"><label for="affiliationIdentifier" class="{{ $labelClass }}">ROR:</label></td>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="affiliationIdentifier" type="text" 
												placeholder="ROR of the affiliation (without https://ror.org/)."
												id="affiliationIdentifier" class="{{ $inputClass }}" />
							@error('affiliationIdentifier')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
						<td></td>
					</tr>
				@else
					<tr>
						<td class="px-4"><label for="creatorAffiliation" class="{{ $labelClass }}">Institution:</label></td>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="creatorAffiliation" type="text" 
													placeholder="Name of the institution."
													id="creatorAffiliation"
								class="{{ $inputClass }}" />
							@error('creatorAffiliation')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
						<td></td>
					</tr>
					<tr>
						<td class="px-4"><label for="affiliationIdentifier" class="{{ $labelClass }}">ROR:</label></td>
					</tr>
					<tr>
						<td class="px-4"><div class="mb-4">
							<input wire:model="affiliationIdentifier" type="text" 
												placeholder="ROR of the institution (without https://ror.org/)."
												id="affiliationIdentifier" class="{{ $inputClass }}" />
							@error('affiliationIdentifier')
								<span class="text-red-500">{{ $message }}</span>
							@enderror
						</div></td>
						<td></td>
					</tr>
				@endif
		</table>
	</fieldset>
	
	<div class="mt-4">
		<x-button type="submit">
			{{ $creator ? 'Update Creator' : 'Create Creator' }}
		</x-button>
	</div>
	
	</form>
</div>
