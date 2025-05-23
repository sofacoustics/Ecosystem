<div>
	<form wire:submit.prevent="save">
			
			{{-- Mandatory metadata --}}
			
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Title (*):</label>
		  <input wire:model="title" type="text" id="title"
				placeholder="the main title of the database"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="productionyear" class="text-gray-700 mb-2 block font-bold">Production Year (*):</label>
		  <input wire:model="productionyear" type="text" id="productionyear"
				placeholder="YYYY, YYYY-YYYY or &quot;unknown&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('productionyear') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="publicationyear" class="text-gray-700 mb-2 block font-bold">Publication Year (*):</label>
		  <input wire:model="publicationyear" type="text" id="publicationyear"
				placeholder="YYYY, YYYY-YYYY or &quot;unknown&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('publicationyear') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<?php /*
		<div class="mb-4">
		  <label for="resource" class="text-gray-700 mb-2 block font-bold">Resource Type (*, non-editable):</label>
			<select wire:model.live="resourcetype">
		<option value="">Select a resource type...</option>
		@foreach(\App\Models\Database::resourcetypesList() as $r => $t)
			<option disabled value="{{ $r }}">
							{{ $t->display }}
						</option>
		@endforeach
		  @error('resourcetype') <span class="text-red-500">{{ $message }}</span> @enderror
			</select>
		  <input wire:model="resource" type="text" id="resource"
				placeholder="Insert an optional description of your database here..."
				disabled
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('resource') <span class="text-red-500">{{ $message }}</span> @enderror
		*/ ?>

		<div class="mb-4">
		  <label for="rights" class="text-gray-700 mb-2 block font-bold">Rights (*): </label>
			<select wire:model.live="controlledrights" required>
		@foreach(\App\Models\Database::controlledrightsList() as $r => $t)
			<option value="{{ $r }}">{{ $t->display }}</option>
		@endforeach
		  @error('controlledrights') <span class="text-red-500">{{ $message }}</span> @enderror
			</select>
			@if ($controlledrights == 21)
		  <input wire:model.live="additionalrights" type="text" id="additionalrights"
				placeholder="Free text for the specification of a proprietary license (not recommended)"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
			@endif
		</div>


		{{-- Optional metadata --}}
		
		<div class="mb-4">
		  <label for="additionaltitle" class="text-gray-700 mb-2 block font-bold">Additional Title:</label>
			<select wire:model.live="additionaltitletype">
		<option value="">Select a title type...</option>
		@foreach(\App\Models\Database::additionaltitletypesList() as $r => $t)
			<option value="{{ $r }}">{{ $t->display }}</option>
		@endforeach
			</select>
		@if ($additionaltitletype != '')
		  <input wire:model="additionaltitle" type="text" id="additionaltitle"
				placeholder="Alternative or additional titles as free text"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('additionaltitle') <span class="text-red-500">{{ $message }}</span> @enderror
		@endif
		</div>
		
		<div class="mb-4">
		  <label for="description" class="text-gray-700 mb-2 block font-bold">Description:</label>
			<select wire:model.live="descriptiontype">
		<option value="">Select a description type...</option>
		@foreach(\App\Models\Database::descriptiontypesList() as $r => $t)
			<option value="{{ $r }}">{{ $t->display }}</option>
		@endforeach
			</select>
		@if ($descriptiontype != '')
		  <input wire:model="description" type="text" id="title"
			  placeholder="Description of the content, e.g. technical remarks or an summary of the dataset."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none" />
		  @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
		@endif
		</div>

		<div class="mb-4">
		  <label for="language" class="text-gray-700 mb-2 block font-bold">Language:</label>
		  <input wire:model="language" type="text" id="language"
				placeholder="Primary language of the resource as defined by ISO 639-3, &quot;eng&quot; for English"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('language') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="datasources" class="text-gray-700 mb-2 block font-bold">Data Source:</label>
		  <input wire:model="datasources" type="text" id="datasources"
				placeholder="The origin of the data (e.g., &quot;acoustically measured&quot; or &quot;computer simulated&quot;)"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('datasources') <span class="text-red-500">{{ $message }}</span> @enderror

		<?php /*   should be SoftwareType and extendable, to be done later
		<div class="mb-4">
		  <label for="software" class="text-gray-700 mb-2 block font-bold">Software:</label>
		  <input wire:model="software" type="text" id="software"
				placeholder="Software name and version (used to create the data)"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('software') <span class="text-red-500">{{ $message }}</span> @enderror  

		<!--   should be DataProcessing and extendable, to be done later
		<div class="mb-4">
		  <label for="processing" class="text-gray-700 mb-2 block font-bold">Processing:</label>
		  <input wire:model="processing" type="text" id="processing"
			  placeholder="Free text, e.g. &quot;statistical nomalisation&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('processing') <span class="text-red-500">{{ $message }}</span> @enderror  

		<!--   should be extendable and with additional fields, to be done later
		<div class="mb-4">
		  <label for="relatedinformation" class="text-gray-700 mb-2 block font-bold">Related Information:</label>
		  <input wire:model="relatedinformation" type="text" id="relatedinformation"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('relatedinformation') <span class="text-red-500">{{ $message }}</span> @enderror   
			
		*/ ?>

		<div>
		  <button
			type="submit"
			class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white"
		  >
			{{ $database ? 'Update' : 'Create' }}
		  </button>
		</div>
	</form>
</div>
