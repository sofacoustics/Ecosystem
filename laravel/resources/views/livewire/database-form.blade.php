<div>
	<form wire:submit.prevent="save">
			
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Title (*):</label>
		  <input wire:model="title" type="text" id="title"
				placeholder="Input the main title of the database. The title does not need to be unique within the Ecosystem."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="additionaltitle" class="text-gray-700 mb-2 block font-bold">Subtitle:</label>
		  <input wire:model="additionaltitle" type="text" id="additionaltitle"
				placeholder="Optional subtitle, which be prominently displayed right under the Title..."
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"/>
		  @error('additionaltitle') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="productionyear" class="text-gray-700 mb-2 block font-bold">Production Year (*):</label>
		  <input wire:model="productionyear" type="text" id="productionyear"
				placeholder="When was the data produced? Format: YYYY, YYYY-YYYY or &quot;unknown&quot;"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('productionyear') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

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
		
		<div class="mb-4">
		  <label for="descriptiongeneral" class="text-gray-700 mb-2 block font-bold">General Description:</label>
			@if($database==null)
				<textarea wire:model="descriptiongeneral" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptiongeneral"
					placeholder="Optional general description of the database."></textarea>
			@else
				<textarea wire:model="descriptiongeneral" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptiongeneral"
					placeholder="Optional general description of the database.">{{ old('descriptiongeneral', $database->descriptiongeneral) }}</textarea>
			@endif
			@error('descriptiongeneral') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="descriptionabstract" class="text-gray-700 mb-2 block font-bold">Abstract:</label>
			@if($database==null)
				<textarea wire:model="descriptionabstract" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionabstract"
					placeholder="Optional abstract for the database."></textarea>
			@else
				<textarea wire:model="descriptionabstract" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionabstract"
					placeholder="Optional abstract for the database.">{{ old('descriptionabstract', $database->descriptionabstract) }}</textarea>
			@endif
		  @error('descriptionabstract') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="descriptionmethods" class="text-gray-700 mb-2 block font-bold">Methods:</label>
			@if($database==null)
				<textarea wire:model="descriptionmethods" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionmethods"
					placeholder="Optional description of the methods used to create the database."></textarea>
			@else
				<textarea wire:model="descriptionmethods" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionmethods"
					placeholder="Optional description of the methods used to create the database.">{{ old('descriptionmethods', $database->descriptionmethods) }}</textarea>
			@endif
		  @error('descriptionmethods') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="descriptionremarks" class="text-gray-700 mb-2 block font-bold">Technical Remarks:</label>
			@if($database==null)
				<textarea wire:model="descriptionremarks" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionremarks"
					placeholder="Optional technical remarks for the database."></textarea>
			@else
				<textarea wire:model="descriptionremarks" class="resizable-textarea text-gray-700 w-full rounded-lg border px-3 py-2" id="descriptionremarks"
					placeholder="Optional technical remarks for the database.">{{ old('descriptionremarks', $database->descriptionremarks) }}</textarea>
			@endif
		  @error('descriptionremarks') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="datasources" class="text-gray-700 mb-2 block font-bold">Data Source:</label>
		  <input wire:model="datasources" type="text" id="datasources"
				placeholder="Optional information about the origin of the data (e.g., &quot;acoustically measured&quot; or &quot;computer simulated&quot;)"
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
