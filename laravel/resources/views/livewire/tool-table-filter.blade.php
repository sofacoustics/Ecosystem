<div>
	<div class="mb-4">
		<input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title" class="border rounded p-2">
		<input type="text" wire:model.live.debounce.500ms="filters.description" placeholder="Filter by Description" class="border rounded p-2">
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
					@auth
						<th class="border p-2">Owner</th>
					@endauth
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($tools as $tool)
					<tr>
						<td class="border p-2">
							<x-button method="GET" action="{{ route('tools.show', [$tool->id]) }}" class="inline">Show</x-button>
						</td>
						<td class="px-6 py-4 whitespace-nowrap">
							<a class="btn btn-primary" href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a>
						</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $tool->productionyear }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $tool->publicationyear }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $tool->updated_at }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $tool->description }}</td>
						@auth
							<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\ToolTableFilter::userName($tool->user_id) }}</td>
						@endauth
					</tr>
				@endforeach
				@if($tools->isEmpty())
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-center" colspan="3">No results found.</td>
					</tr>
				@endif
			</tbody>
	</table>
</div>

