<div>

	{{-- quick filters by resource type (same filter as the "... by Type" dropdown) --}}
	<div class="flex flex-wrap gap-2 mb-2">
		<button type="button" wire:click="$set('filters.resourcetype', 0)"
			class="rounded px-3 py-1 {{ (int) $filters['resourcetype'] === 0 ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">All</button>
		@foreach($quickFilters as $id => $label)
			<button type="button" wire:click="$set('filters.resourcetype', {{ $id }})"
				class="rounded px-3 py-1 {{ (int) $filters['resourcetype'] === (int) $id ? 'bg-blue-500 text-white' : 'bg-gray-200 hover:bg-gray-300' }}">{{ $label }}</button>
		@endforeach
	</div>

	<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<th class="border p-2"></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title..." class="border rounded p-2"></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.productionyear" placeholder="... by Produced Year(s)" class="border rounded p-2"></th>
			<th>
				<select wire:model.live="filters.resourcetype">
					<option value="0">... by Type</option>
					@foreach($resourcetypes as $rt)
						<option value="{{ $rt->id }}">{{ $rt->value === 'TEXT' ? 'Text & Documentation' : $rt->display }}</option>
					@endforeach
					@error('resourcetype') <span class="text-red-500">{{ $message }}</span> @enderror
				</select>
			</th>
			@auth
				<th class="border p-2"></th>
			@endauth
			<th><input type="text" wire:model.live.debounce.500ms="filters.keyword" placeholder="... by Keyword" class="border rounded p-2"></th>
			<th><button wire:click="clearFilters" class="bg-gray-200 rounded p-2">Clear Filters</button></th>
		</thead>
		<thead class="bg-gray-50">
			<th class="border p-2 min-w-[10ch]">
				{{-- tooltip describes the order after the next click (ascending: no DOI first; descending: published first) --}}
				<button wire:click="sortBy('doi')" title="{{ ($sortField === 'doi' && $sortAsc)
					? 'Click to sort by DOI state with tools permanently published with DOI at the top'
					: 'Click to sort by DOI state with tools without DOI at the top' }}">
					<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI?"  style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
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
			<th class="border p-2">
				<button wire:click="sortBy('resourcetype')">Type
					@if ($sortField === 'resourcetype')
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
				<th class="border p-2">
					<button wire:click="sortBy('user')">Uploaded by
						@if ($sortField === 'user')
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
			@endauth
			<th class="border p-2">
				<button wire:click="sortBy('keywords')" title="Sort by the first keyword; tools without keywords are listed at the end">Keywords
					@if ($sortField === 'keywords')
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
			@foreach($tools as $tool)
				<tr>
					<td class="text-center">
						@if(empty($tool->doi))
							<img id="nodoi" src="{{ asset('images/noDOI.png') }}" alt="noDOI" title="No DOI assigned" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
						@elseif($tool->radar_status == 4)
							<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI" title="Persistently published with DOI" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
						@else
							<img id="doiassigned" src="{{ asset('images/DOI.svg') }}" alt="DOI assigned" title="DOI assigned (not yet persistently published)" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em; filter: grayscale(100%);">
						@endif
					</td>
					<td class="px-6 py-4 whitespace-normal">
						<a class="btn btn-primary" href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a><br>
						<small>{{ $tool->additionaltitle }}</small>
					</td>
					<td class="px-6 py-4 text-center">{{ $tool->productionyear }}</td>
					<td class="px-6 py-4 text-center">{{ \App\Models\Tool::resourcetypeLabel($tool->resourcetype) }}</td>
					@auth
						<td class="px-6 py-4 text-center">{{ \App\Livewire\ToolTableFilter::userName($tool->user_id) }}</td>
					@endauth
					<td class="px-6 py-4 whitespace-nowrap text-center">{!! nl2br(e(\App\Livewire\ToolTableFilter::getKeywords($tool->id))) !!}</td>
					<td class="px-6 py-4 text-center">{{ $tool->updated_at }}</td>
				</tr>
			@endforeach
			@if($tools->isEmpty())
				<tr>
					<td class="px-6 py-4 whitespace-nowrap text-center" colspan="3">No tools found.</td>
				</tr>
			@endif
		</tbody>
	</table>
</div>

