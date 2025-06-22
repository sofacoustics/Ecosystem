@php
	// https://laravel.com/docs/11.x/blade#conditional-classes
	$labelClass = 'text-gray-700 mb-2 inline font-bold px-3';
	$selectClass = 'text-gray-700 mb-2 font-bold';
	$inputClass = 'text-gray-700 flex-grow min-w-0 border px-3 mb-2';
	$buttonClass = 'bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white';
	$tabClass = 'bg-blue-500 hover:bg-blue-700 rounded font-bold text-white mx-1 my-1 py-1 px-2';
	$tabDisabledClass = 'gray-400 bg-gray-400 text-white rounded font-bold mx-1 my-1 py-1 px-2';
@endphp
<div>
	@if ($relatedidentifier) 
		<h3>Edit the relation:</h3>
	@else
		<h3>Add a new relation:</h3>
	@endif
	<div class="mx-auto shadow-md box-border">

			<button wire:click="selectTab('ecosystem')"
				class="focus:outline-none transition duration-150 ease-in-out
					{{ $activeTab === 'ecosystem' ? $tabClass : $tabDisabledClass }}">
				Relation within the Ecosystem
			</button>
			<button wire:click="selectTab('general')"
				class="focus:outline-none transition duration-150 ease-in-out
				 {{ $activeTab === 'general' ? $tabClass : $tabDisabledClass }}">
				General relation
			</button>
		<div class="mt-6 p-6">
			@if($activeTab === 'ecosystem')
				<form wire:submit.prevent="saveEcosystem">
					<div class="flex items-center">
						<label class="{{ $labelClass }}" for="ecosystemrelation">{{ $prefix }}:</label>
						<select wire:model.live="ecosystemrelation" class="{{ $selectClass }}">
							<option value="1">Is Described By</option>
							<option value="2">Describes</option>
							<option value="3">Was Created With</option>
							<option value="4">Was Involved of Creation of</option>
							<option value="5">Can Be Processed By</option>
							<option value="6">Processes</option>
						</select>
						@error('ecosystemrelation')
							<span class="text-red-500">{{ $message }}</span>
						@enderror
						
						<select wire:model.live="ecosystemrelatedable" class="{{ $selectClass }}">
							@foreach($ecosystemrelatedable_ids as $r => $t)
								<option value="{{ $t }}">{{ $ecosystemrelatedable_names[$r] }}</option>
							@endforeach
						</select>
						@error('ecosystemrelatedables') <span class="text-red-500">{{ $message }}</span> @enderror
					</div>
					
					<div class="mt-4">
						<button type="submit" class="{{ $buttonClass }}">
							{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
						</button>
					</div>
				</form>
			@endif
				
			@if($activeTab === 'general')
				<form wire:submit.prevent="saveGeneral">
					<div class="flex items-center">
						<label class="{{ $labelClass }}" for="relationtype">{{ $prefix }}:</label>
						<select wire:model.live="relationtype" class="{{ $selectClass }}">
							<option value="">Select a relation...</option>
							@foreach(\App\Models\Metadataschema::list_ids('relationType') as $t)
								<option value="{{ $t->id }}">{{ \App\Models\Metadataschema::display($t->id) }}</option>
							@endforeach
						</select>
						@error('relationtype')
							<span class="text-red-500">{{ $message }}</span>
						@enderror
						
						<input wire:model="name" type="text" 
							placeholder="The identifier, e.g., the DOI (if type is DOI) or an URL (if type is URL)."
							id="name"
							class="{{ $inputClass }}"/>
						@error('name') <span class="text-red-500">{{ $message }}</span> @enderror

						<select wire:model.live="relatedidentifiertype" class="{{ $selectClass }}">
							@foreach(\App\Models\Metadataschema::list_ids('relatedIdentifierType') as $t)
								<option value="{{ $t->id }}">{{ \App\Models\Metadataschema::display($t->id) }}</option>
							@endforeach
						</select>
						@error('relatedidentifiertype') <span class="text-red-500">{{ $message }}</span> @enderror
					</div>
					<div class="mt-4">
						<button type="submit" class="{{ $buttonClass }}">
							{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
						</button>
					</div>
				</form>
			@endif
		</div>
	</div>
</div>
