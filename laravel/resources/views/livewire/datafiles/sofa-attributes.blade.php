<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<div class="mb-8">
		@if (!empty($csvRows))
			<table class="min-w-full border border-gray-300 rounded">
				<thead>
					<th class="px-2 py-1 bg-gray-100 border text-left"></th>
					<th class="px-2 py-1 bg-gray-100 border text-center" colspan="5">Dimensions</th>
				</thead>
				<thead>
					@foreach ($csvRows[0] as $header)
						<th class="px-2 py-1 bg-gray-100 border">{{ $header }}</th>
					@endforeach
				</thead>
				<tbody>
					@foreach (array_slice($csvRows, 1) as $row)
						<tr>
							@foreach ($row as $cell)
								<td class="px-2 py-1 border text-center">{{ $cell }}</td>
							@endforeach
						</tr>
					@endforeach
				</tbody>
			</table>
		@else
				<div class="text-red-500 p-2">
					.sofa_dim.csv file could not be read: ({{ $csvPath ?? 'unknown path' }})
				</div>
		@endif
	</div>

	<div class="expandable-box" wire:click="toggleExpand">
		@if($isExpanded==false)
			<div class="box-content collapsed-preview">
				<small><button class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Show more SOFA properties...</button></small>
			</div>
		@else
			@if(!empty($csvRowsProp))
				<table class="min-w-full border border-gray-300 rounded">
					<thead>
						@foreach ($csvRowsProp[0] as $header)
								<th class="px-2 py-1 bg-gray-100 border text-left">{{ $header }}</th>
						@endforeach
					</thead>
					<tbody>
						@foreach (array_slice($csvRowsProp, 1) as $row)
							<tr>
								@foreach ($row as $cell)
									<td class="px-2 py-1 border">{{ $cell }}</td>
								@endforeach
							</tr>
						@endforeach
					</tbody>
				</table>
			@else
					<div class="text-red-500 p-2">
							.sofa_prop.csv file could not be read: ({{ $csvPathProp ?? 'unknown path' }})
					</div>
			@endif
		@endif
	</div>

	<br>
</div>
