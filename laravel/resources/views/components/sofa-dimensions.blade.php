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
		@endif
	</div>
