<div>
	<form wire:submit.prevent="save">
			
			{{-- Mandatory metadata --}}
			
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Title (*):</label>
		  <input wire:model="title" type="text" id="title"
				placeholder="Input the main title of the database. The title does not need to be unique within the Ecosystem."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="productionyear" class="text-gray-700 mb-2 block font-bold">Production Year (*):</label>
		  <input wire:model="productionyear" type="text" id="productionyear"
				placeholder="When was the data produced? Format: YYYY, YYYY-YYYY or &quot;unknown&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('productionyear') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<?php /*
		<div class="mb-4">
		  <label for="publicationyear" class="text-gray-700 mb-2 block font-bold">Publication Year (*):</label>
		  <input wire:model="publicationyear" type="text" id="publicationyear"
				placeholder="YYYY, YYYY-YYYY or &quot;unknown&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('publicationyear') <span class="text-red-500">{{ $message }}</span> @enderror
		</div> */ ?>

		<div class="mb-4">
		  <label for="rights" class="text-gray-700 mb-2 block font-bold">Rights (*): </label>
			<select wire:model.live="controlledrights" required>
		@foreach(\App\Models\Database::controlledrightsList() as $r => $t)
			<option value="{{ $r }}">{{ $t->display }}</option>
		@endforeach
		  @error('controlledrights') <span class="text-red-500">{{ $message }}</span> @enderror
			</select>
			@if ($controlledrights == $controlledrights_other_id)
		  <input wire:model.live="additionalrights" type="text" id="additionalrights"
				placeholder="If the license is 'Other', provide a clear name for your license here. Note: 'Other' licenses are not recommended..."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
			@endif
		</div>


		{{-- Optional metadata --}}
		
		<div class="mb-4">
		  <label for="additionaltitle" class="text-gray-700 mb-2 block font-bold">Subtitle:</label>
		  <input wire:model="additionaltitle" type="text" id="additionaltitle"
				placeholder="Optional subtitle, which be prominently displayed right under the Title..."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('additionaltitle') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="descriptiongeneral" class="text-gray-700 mb-2 block font-bold">General Description:</label>
		  <textarea wire:model="descriptiongeneral" type="text" id="descriptiongeneral"
			  placeholder="Optional general description of the database."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none" />
		  @error('descriptiongeneral') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<?php /*
		<div class="mb-4">
		  <label for="language" class="text-gray-700 mb-2 block font-bold">Language:</label>
		  <input wire:model="language" type="text" id="language"
				placeholder="Primary language of the resource as defined by ISO 639-3, &quot;eng&quot; for English"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('language') <span class="text-red-500">{{ $message }}</span> @enderror
		</div> */ ?>

		<div class="mb-4">
		  <label for="datasources" class="text-gray-700 mb-2 block font-bold">Data Source:</label>
		  <input wire:model="datasources" type="text" id="datasources"
				placeholder="The origin of the data (e.g., &quot;acoustically measured&quot; or &quot;computer simulated&quot;)"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('datasources') <span class="text-red-500">{{ $message }}</span> @enderror

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
