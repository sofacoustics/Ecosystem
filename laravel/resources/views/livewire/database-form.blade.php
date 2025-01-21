<div>
    <form wire:submit.prevent="save">
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Title</label>
		  <input
			wire:model="title"
			type="text"
			id="title"
			class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
			required
		  />
		  @error('title') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
		<div class="mb-4">
		  <label for="title" class="text-gray-700 mb-2 block font-bold">Description</label>
		  <input
			wire:model="description"
			type="text"
			id="title"
			class="text-gray-700 w-full rounded-lg border px-3 py-2 focus:outline-none"
			required
		  />
		  @error('description') <span class="text-red-500">{{ $message }}</span> @enderror
		</div>
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
