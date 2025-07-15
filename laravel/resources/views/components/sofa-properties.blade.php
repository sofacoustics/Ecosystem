@if (!empty($csvRowsProp))
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
	No SOFA properties found
@endif

