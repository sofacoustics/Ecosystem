<div>
	<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<th></th>
			<th></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.name" placeholder="Filter by Name..." class="border rounded p-2"></th>
			<th></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.description" placeholder="... by Description" class="border rounded p-2"></th>
			<th></th>
			<th><button wire:click="clearFilters" class="bg-gray-200 rounded p-2">Clear Filters</button></th>
		</thead>
		<thead class="bg-gray-50">
			@can('create', [App\Models\Dataset::class, $database])
				<th class="border p-2 w-48">Command</th>
			@endcan
			<th class="border p-2" title="This sorts by the internal ID of each dataset.">
				<button wire:click="sortBy('id')">#
					@if ($sortField === 'id')
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
				<button wire:click="sortBy('name')">Name
					@if ($sortField === 'name')
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
			<th class="border px-6 py-2 text-left">
				<button wire:click="sortBy('count')" class="text-left"># Datafiles
					@if ($sortField === 'count')
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
			<th class="border p-2">
				<button wire:click="sortBy('updated_at')">Updated (GMT)
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
				<button wire:click="sortBy('created_at')">Created (GMT)
					@if ($sortField === 'created_at')
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
		</thead>
		<tbody class="bg-white divide-y divide-gray-200">
			@foreach($datasets as $dataset)
				<tr>
					@can('update', [App\Models\Dataset::class, $dataset])
						<td class="border p-2">
							<x-button method="DELETE" class="inline" action="{{ route('datasets.destroy', [$dataset]) }}">Delete</x-button>
							<x-button method="GET" class="inline" action="{{ route('datasets.edit', $dataset) }}" >Edit</x-button>
						</td>
					@endcan
					<td class="border p-2 text-center">#{{ $loop->index+1}}</td>
					<td class="border p-2 text-center"><a href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->name }}</a></td>
					<td class="border p-2 text-center">{{ count($dataset->datafiles) }}</td>
					<td class="border p-2">{{ $dataset->description }}</td>
					<td class="border p-2 text-center"><small>{{ $dataset->updated_at }}</small></td>
					<td class="border p-2 text-center"><small>{{ $dataset->created_at }}</small></td>
				</tr>
			@endforeach
		</tbody>
	</table>
</div>

