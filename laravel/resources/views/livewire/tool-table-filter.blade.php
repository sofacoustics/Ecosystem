<div>

	<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<th class="border p-2"></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.title" placeholder="Filter by Title..." class="border rounded p-2"></th>
			<th><input type="text" wire:model.live.debounce.500ms="filters.productionyear" placeholder="... by Produced Year(s)" class="border rounded p-2"></th>
			<th>
				<select wire:model.live="filters.resourcetype">
					<option value="0">... by Type</option>
					@foreach(\App\Models\Tool::resourcetypesList() as $r => $t)
						<option value="{{ $r+1 }}">{{ $t->display }}</option>
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
				<button wire:click="sortBy('doi')">
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
			@foreach($tools as $tool)
				<tr>
					<td class="text-center">
						@if($tool->doi)
							<img id="doi" src="{{ asset('images/DOI.svg') }}" alt="DOI"  style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
						@else
							<img id="nodoi" src="{{ asset('images/noDOI.png') }}" alt="noDOI" style="display: block; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
						@endif
					</td>
					<td class="px-6 py-4 whitespace-nowrap">
						<a class="btn btn-primary" href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a><br>
						<small>{{ $tool->additionaltitle }}</small>
					</td>
					<td class="px-6 py-4 whitespace-nowrap text-center">{{ $tool->productionyear }}</td>
					<td class="px-6 py-4 whitespace-nowrap text-center">{{ \App\Models\Tool::resourcetypeDisplay($tool->resourcetype) }}</td>
					@auth
						<td class="px-6 py-4 whitespace-nowrap text-center">{{ \App\Livewire\ToolTableFilter::userName($tool->user_id) }}</td>
					@endauth
					<td class="px-6 py-4 whitespace-nowrap text-center">{{ \App\Livewire\ToolTableFilter::getKeywords($tool->id) }}</td>
					<td class="px-6 py-4 whitespace-nowrap text-center">{{ $tool->updated_at }}</td>
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

