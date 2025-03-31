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
					<th class="border p-2">
						<button wire:click="sortBy('title')">Title
							@if ($sortField === 'title')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					</th>
					<th class="border p-2">
						<button wire:click="sortBy('published')">Published
							@if ($sortField === 'published')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					</th>
					<th class="border p-2">
						<button wire:click="sortBy('productionyear')">Produced Year
							@if ($sortField === 'productionyear')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					</th>
					<th class="border p-2">
						<button wire:click="sortBy('publicationyear')">Published Year
							@if ($sortField === 'publicationyear')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					<th class="border p-2">
						<button wire:click="sortBy('updated_at')">Last update
							@if ($sortField === 'updated_at')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					</th>
					<th class="border p-2">
						<button wire:click="sortBy('description')">Description
							@if ($sortField === 'description')
								@if ($sortAsc)
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12" />
									</svg>
								@else
									<svg xmlns="http://www.w3.org/2000/svg" class="inline-block h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
											<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6" />
									</svg>
								@endif
							@endif
						</button>
					</th>
					<th class="border p-2"># Datasets</th>
					@auth
						<th class="border p-2">Owner</th>
					@endauth
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($databases as $database)
					<tr>
						<td class="border p-2">
							@if($database->published || ($user_id == $database->user_id))
								<x-button method="GET" action="{{ route('databases.show', [$database->id]) }}" class="inline">Show</x-button>
							@endif
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
						<td class="px-6 py-4 whitespace-nowrap">
							@if($database->published)
									X
							@else
								  -
							@endif
						</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->productionyear }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->publicationyear }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->updated_at }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->description }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::countDatasets($database->id) }}</td>
						@auth
							<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::userName($database->user_id) }}</td>
						@endauth
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

