<div>
	<div class="mb-4">
		<input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title" class="border rounded p-2">
		<input type="text" wire:model.live.debounce.500ms="filters.productionyear" placeholder="... by Produced Year" class="border rounded p-2">
		<input type="text" wire:model.live.debounce.500ms="filters.keywords" placeholder="... by Keyword" class="border rounded p-2">
		<button wire:click="clearFilters" class="bg-gray-200 rounded p-2">Clear Filters</button>
	</div>

	<!-- <table class="min-w-full divide-y divide-gray-200">-->
	<table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<tr>
					<th class="border p-2">
						<button wire:click="sortBy('visible')">
							<img id="visible" src="{{ asset('images/visible.svg') }}" alt="visible" style="height: 1em;">
							@if ($sortField === 'visible')
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
						<button wire:click="sortBy('doi')">
							<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI" style="height: 1em;">
							@if ($sortField === 'visible')
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
							<br><small>Subtitle</small>
						</button>
					</th>
					<th class="border p-2">
						<button wire:click="sortBy('productionyear')">Produced
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
					<th class="border p-2"># Datasets</th>
					<th class="border p-2">Keywords</th>
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
					@auth
						<th class="border p-2">Uploaded by</th>
					@endauth
				</tr>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($databases as $database)
					@if($database->visible || ($user_id == $database->user_id))
					<tr>
						<td class="px-6 py-4 whitespace-nowrap">
							@if($database->visible)
								<img id="visible" src="{{ asset('images/visible.svg') }}" alt="V" style="height: 1em;">
							@else
								<img id="hidden" src="{{ asset('images/hidden.svg') }}" alt="H" style="height: 1em;">
							@endif
						</td>
						<td class="px-6 py-4 whitespace-nowrap">
							@if($database->doi)
								<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI" style="height: 1em;">
							@else
								<img id="nodoi" src="{{ asset('images/noDOI.png') }}" alt="noDOI" style="height: 1em;">
							@endif
						</td>
						<td class="px-6 py-4 whitespace-nowrap">
							<a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a><br>
							<small>{{ $database->additionaltitle }}</small>
						</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->productionyear }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::countDatasets($database->id) }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::getKeywords($database->id) }}</td>
						<td class="px-6 py-4 whitespace-nowrap">{{ $database->updated_at }}</td>
						@auth
							<td class="px-6 py-4 whitespace-nowrap">{{ \App\Livewire\DatabaseTableFilter::userName($database->user_id) }}</td>
						@endauth
					</tr>
					@endif
				@endforeach
				@if($databases->isEmpty())
					<tr>
						<td class="px-6 py-4 whitespace-nowrap text-center" colspan="3">No results found.</td>
					</tr>
				@endif
			</tbody>
	</table>
</div>

