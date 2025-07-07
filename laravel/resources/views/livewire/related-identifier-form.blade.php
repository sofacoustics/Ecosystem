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
	
	<div>

		<fieldset>
			<div class="py-4">
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp;
				<input class="p-2" type="radio" id="database" name="type" value="database" wire:model.live="option"/>
				<label for="database">&nbsp; Relation within an Ecosystem Database</label>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				<input class="p-2" type="radio" id="tool" name="type" value="tool" wire:model.live="option"/>
				<label for="tool">&nbsp; Relation within an Ecosystem Tool/Document</label>
				&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; 
				<input class="p-2" type="radio" id="external" name="type" value="external" wire:model.live="option"/>
				<label for="external">&nbsp; Relation to external resources</label>
			</div>

			<div class="mt-6 p-6">
				@if($option === 'database')
					<form wire:submit.prevent="saveDatabase">
						<div class="flex items-center">
							<label class="{{ $labelClass }}" for="databaserelation">{{ $prefix }}...</label>
							<select wire:model.live="databaserelation" class="{{ $selectClass }}" required
								title="Relation Type">
								<option value="-1">{{ \App\Models\RelatedIdentifier::displayRelation(-1) }} ...</option>
								@foreach(\App\Models\Metadataschema::list_ids('relationType') as $t)
									<option value="{{ $t->id }}">{{ strtolower(\App\Models\Metadataschema::display($t->id)) }} ...</option>
								@endforeach
							</select>
							
							<select wire:model.live="databaserelatedable" class="{{ $selectClass }}" required
								title="Visible Databases">
								@foreach($databaserelatedable_ids as $r => $t)
									<option value="{{ $t }}">{{ $databaserelatedable_names[$r] }}</option>
								@endforeach
							</select>
						</div>
						@error('databaserelation') <span class="text-red-500">{{ $message }}</span> @enderror
						@error('databaserelatedable') <span class="text-red-500">{{ $message }}</span> @enderror
						<div class="mt-4">
							<button type="submit" class="{{ $buttonClass }}">
								{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
							</button>
						</div>
					</form>
				@endif
	 
				@if($option === 'tool')
					<form wire:submit.prevent="saveTool">
						<div class="flex items-center">
							<label class="{{ $labelClass }}" for="toolrelation">{{ $prefix }}...</label>
							<select wire:model.live="toolrelation" class="{{ $selectClass }}" required
								title="Relation Type">
								<option value="-1">{{ \App\Models\RelatedIdentifier::displayRelation(-1) }} ...</option>
								<option value="-2">{{ \App\Models\RelatedIdentifier::displayRelation(-2) }} ...</option>
								<option value="-3">{{ \App\Models\RelatedIdentifier::displayRelation(-3) }} ...</option>
								@foreach(\App\Models\Metadataschema::list_ids('relationType') as $t)
									<option value="{{ $t->id }}">{{ strtolower(\App\Models\Metadataschema::display($t->id)) }} ...</option>
								@endforeach
							</select>
							
							<select wire:model.live="toolrelatedable" class="{{ $selectClass }}" required
								title="Tools and Documents">
								@foreach($toolrelatedable_ids as $r => $t)
									<option value="{{ $t }}">{{ $toolrelatedable_names[$r] }}</option>
								@endforeach
							</select>
						</div>
						@error('toolrelation') <span class="text-red-500">{{ $message }}</span> @enderror
						@error('toolrelatedable') <span class="text-red-500">{{ $message }}</span> @enderror
						<div class="mt-4">
							<button type="submit" class="{{ $buttonClass }}">
								{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
							</button>
						</div>
					</form>
				@endif

				@if($option === 'external')
					<form wire:submit.prevent="saveExternal">
						<div class="flex items-center">
							<label class="{{ $labelClass }}" for="relationtype">{{ $prefix }}...</label>
							<select wire:model.live="relationtype" class="{{ $selectClass }}" required
								title="Relation Type">
								<option value="">Select a relation...</option>
								@foreach(\App\Models\Metadataschema::list_ids('relationType') as $t)
									<option value="{{ $t->id }}">{{ strtolower(\App\Models\Metadataschema::display($t->id)) }}...</option>
								@endforeach
							</select>
							
							<input wire:model="name" type="text" 
								placeholder="... the identifier, e.g., a DOI or an URL (depending on the following type)"
								id="name"
								class="{{ $inputClass }}"/>

							<select wire:model.live="relatedidentifiertype" class="{{ $selectClass }}" required
								title="Identifier Type">
								@foreach(\App\Models\Metadataschema::list_ids('relatedIdentifierType') as $t)
									<option value="{{ $t->id }}">{{ \App\Models\Metadataschema::display($t->id) }}</option>
								@endforeach
							</select>
						</div>
						@error('relationtype') <span class="text-red-500">{{ $message }}</span> @enderror
						@error('name') <span class="text-red-500">{{ $message }}</span> @enderror
						@error('relatedidentifiertype') <span class="text-red-500">{{ $message }}</span> @enderror
						<div class="mt-4">
							<button type="submit" class="{{ $buttonClass }}">
								{{ $relatedidentifier ? 'Update Relation' : 'Create Relation' }}
							</button>
						</div>
					</form>
				@endif
			</div>
		</fieldset>
	</div>
</div>
