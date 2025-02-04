<div>
    <form wire:submit.prevent="save">
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Title: (*)</label>
		  <input wire:model="title" type="text" id="title"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Additional Title:</label>
			<select wire:model.live="additionaltitletype">
        <option value="">Select a title type...</option>
        @foreach(\App\Models\Database::additionaltitletypes() as $r => $t)
            <option value="{{ $r }}">{{ $t->display }}</option>
        @endforeach
			</select>
		  <input wire:model="additionaltitle" type="text" id="additionaltitle"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				/>
		  @error('additionaltitle') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Description:</label>
		  <input wire:model="description" type="text" id="title"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none" />
		  @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>

		<div class="mb-4">
		  <label for="productionyear" class="text-gray-700 mb-2 block font-bold">Production Year (*):</label>
		  <input wire:model="productionyear" type="text" id="productionyear"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('productionyear') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="publicationyear" class="text-gray-700 mb-2 block font-bold">Publication Year (*):</label>
		  <input wire:model="publicationyear" type="text" id="publicationyear"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				required />
		  @error('publicationyear') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="language" class="text-gray-700 mb-2 block font-bold">Language:</label>
		  <input wire:model="language" type="text" id="language"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('language') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="resource" class="text-gray-700 mb-2 block font-bold">Resource Type (*) and optional Description:</label>
			<select wire:model.live="resourcetype">
        <option value="">Select a resource type...</option>
        @foreach(\App\Models\Database::resourcetypes() as $r => $t)
            <option value="{{ $r }}">{{ $t->display }}</option>
        @endforeach
		  @error('resourcetype') <span class="text-red-500">{{ $message }}</span> @enderror
			</select>
		  <input wire:model="resource" type="text" id="resource"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
			   />
		  @error('resource') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="datasources" class="text-gray-700 mb-2 block font-bold">Data Sources</label>
		  <input wire:model="datasources" type="text" id="datasources"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('datasources') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="software" class="text-gray-700 mb-2 block font-bold">Software:</label>
		  <input wire:model="software" type="text" id="software"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('software') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="processing" class="text-gray-700 mb-2 block font-bold">Processing:</label>
		  <input wire:model="processing" type="text" id="processing"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('processing') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="relatedinformation" class="text-gray-700 mb-2 block font-bold">Related Information:</label>
		  <input wire:model="relatedinformation" type="text" id="relatedinformation"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
				 />
		  @error('relatedinformation') <span class="text-red-500">{{ $message }}</span> @enderror

		<div class="mb-4">
		  <label for="rights" class="text-gray-700 mb-2 block font-bold">Rights: </label>
		  <input wire:model="rights" type="text" id="rights"
				class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
			  />
		  @error('rights') <span class="text-red-500">{{ $message }}</span> @enderror



		<div>
		  <button
			type="submit"
			class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white"
		  >
			{{ $database ? 'Update Database' : 'Create Database' }}
		  </button>
		</div>
    </form>
</div>
