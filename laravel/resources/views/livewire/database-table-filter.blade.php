<div>
	<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<th></th>
			<th></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title..." class="border rounded p-2"></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.productionyear" placeholder="... by Produced Year(s)" class="border rounded p-2"></th>
			<th></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.keyword" placeholder="... by Keyword" class="border rounded p-2"></th>
			<th><button wire:click="clearFilters" class="bg-gray-200 rounded p-2">Clear Filters</button></th>
		</thead>
		<thead class="bg-gray-50">
			<th class="border p-2">
				<button wire:click="sortBy('visible')">
					<img id="visible" src="{{ asset('images/visible.svg') }}" alt="Visible?" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
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
					<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI?" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
					@if ($sortField === 'doi')
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
				<button wire:click="sortBy('title')" class="text-left">Title
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
				<button wire:click="sortBy('productionyear')">Produced Year(s)
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
			@auth
				<th class="border p-2">Uploaded by</th>
			@endauth
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
		</thead>
		<tbody class="bg-white divide-y divide-gray-200">
			@foreach($databases as $database)
				@can('view', $database)
				<tr>
					<td class="text-center">
						@if($database->visible)
							<img id="visible" src="{{ asset('images/visible.svg') }}" alt="V" style="display: block; margin: 0 auto; height:2em;">
						@else
							<img id="hidden" src="{{ asset('images/hidden.svg') }}" alt="H" style="display: block; margin: 0 auto; height:2em;">
						@endif
					</td>
					<td class="text-center">
						@if($database->doi)
							<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI"  style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em;">
						@else
							<img id="nodoi" src="{{ asset('images/noDOI.png') }}" alt="noDOI"  style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em;">
						@endif
					</td>
					<td class="px-6 py-4 whitespace-normal">
						<a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a><br>
						<small>{{ $database->additionaltitle }}</small>
					</td>
					<td class="px-6 py-4 text-center">{{ $database->productionyear }}</td>
					<td class="px-6 py-4 text-center">{{ \App\Livewire\DatabaseTableFilter::countDatasets($database->id) }}</td>
					@auth
						<td class="px-6 py-4 text-center">{{ \App\Livewire\DatabaseTableFilter::userName($database->user_id) }}</td>
					@endauth
					<td class="px-6 py-4 whitespace-nowrap text-center">{!! nl2br(e(\App\Livewire\DatabaseTableFilter::getKeywords($database->id))) !!}</td>
					<td class="px-6 py-4 text-center">{{ $database->updated_at }}</td>
				</tr>
				@endcan
			@endforeach
			@if($databases->isEmpty())
				<tr>
					<td class="px-6 py-4 whitespace-nowrap text-center" colspan="3">No databases found.</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>

