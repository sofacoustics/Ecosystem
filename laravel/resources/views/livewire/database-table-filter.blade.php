<div>
    <div class="mb-4">
        <input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title" class="border rounded p-2">
        <input type="text" wire:model.live.debounce.500ms="filters.description" placeholder="Filter by Description" class="border rounded p-2">
        <!-- <select wire:model.live="filters.descriptiontype" class="border rounded p-2">
            <option value="">All descriptiontypes</option>
            {{-- @foreach($descriptiontypes as $descriptiontype)
                <option value="{{ $descriptiontype }}">{{ ucfirst($descriptiontype) }}</option>
            @endforeach --}}
        </select> --!>
        <button wire:click="clearFilters" class="bg-gray-200 rounded p-2">Clear Filters</button>
    </div>

    <!-- <table class="min-w-full divide-y divide-gray-200">-->
    <table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<tr>
					<th class="border p-2">Command</th>
					<th class="border p-2">Title</th>
					<th class="border p-2"># Datasets</th>
					<th class="border p-2">Description</th>
					<th class="border p-2">Owner</th>
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($databases as $database)
					<tr>
						<td class="border p-2">
							<x-button method="GET" action="{{ route('databases.show', [$database->id]) }}" class="inline">Show</x-button>
							@can('update', $database)
								<x-button method="GET" action="{{ route('databases.edit', [$database->id]) }}" class="inline">Edit Metadata</x-button>
								<x-button method="GET" class="inline" action="{{ route('databases.datasets.create', [$database->id]) }}">Upload</x-button>
								<x-button method="GET" class="inline" action="{{ route('databases.datasets.bulkupload', [$database->id]) }}">Bulk Upload</x-button>
							@endcan
							@can('delete', $database)
								<x-button method="DELETE" action="{{ route('databases.destroy', [$database->id]) }}" class="inline">Delete</x-button>
							@endcan
						</td>
						<td class="px-6 py-4 whitespace-nowrap">
							<a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
						</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::countDatasets($database->id) }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->description }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::userName($database->user_id) }}</td>
					</tr>
				@endforeach
				@if($databases->isEmpty())
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-center" colspan="3">No results found.</td>
					</tr>
				@endif
			</tbody>
    </table>
</div>

