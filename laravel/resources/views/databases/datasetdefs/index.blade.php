<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database tabTitle='Definition'/>
	</x-slot>
	@if(count($database->datasetdefs)>0)		
		<h3>Each dataset is defined as followed:</h3>
		<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<tr>
				<th class="border p-2">#</th>
				@if($edits)
					<th class="border p-2" colspan="5">Commands</th>
				@else
					<th class="border p-2" colspan="5"></th>
				@endif
				<th class="border p-2">Name</th>
				<th class="border p-2">Type</th>
				<th class="border p-2">Widget</th>
				<th class="border p-2"># Datafiles</th>
				<th class="border p-2">Description</th>
				@role('admin')
					<th class="border p-2">ID</th>
				@endrole
			</tr>
		</thead>

		<tbody class="bg-white divide-y divide-gray-200">
		@foreach($database->datasetdefs as $datasetdef)
			<tr>
				<td class="text-center">{{ ($loop->index)+1 }}</td>
				@can('update', [$datasetdef, $database])
					<td>
						<x-button method="GET" class="inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">Edit</x-button>
					</td>
					<td>
						<x-button method="GET" class="inline" action="{{ route('datasetdefs.duplicate', [$datasetdef]) }}">Duplicate</x-button>
					</td>
				@else
					<td></td><td></td>
				@endcan
				@can('move', [$datasetdef, $database])
					<td>
						@if($loop->index > 0)
							<x-button method="GET" action="{{ route('datasetdefs.up', $datasetdef) }}" class="inline">
								&uarr;
							</x-button>
						@endif
					</td>
					<td>
						@if($loop->index < count($database->datasetdefs)-1)
						<x-button method="GET" action="{{ route('datasetdefs.down', [$datasetdef]) }}" class="inline">
							&darr;
						</x-button>
						@endif
					</td>
				@else
					<td></td><td></td>
				@endcan
				@can('delete', [$datasetdef, $database])
					<td>
						<x-button method="DELETE" class="inline" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">Delete</x-button>
					</td>
				@else
					<td></td>
				@endcan
				<td class="px-6 py-4 whitespace-nowrap">{{ $datasetdef->name }}</td>
				<td class="px-6 py-4 whitespace-nowrap">{{ $datasetdef->datafiletype->name }}</td>
				<td class="px-6 py-4 whitespace-nowrap">
					@if($datasetdef->widget)
						{{ $datasetdef->widget->name }}
					@else
						No widget
					@endif
				</td>
				<td class="px-6 py-4 whitespace-nowrap">{{ count($datasetdef->datafiles) }}</td>
				<td class="px-6 py-4 whitespace-nowrap">{{ $datasetdef->description }}</td>
				@role('admin') 
					<td class="px-6 py-4 whitespace-nowrap">{{ $datasetdef->datafiletype->id }}</td>
				@endrole
			</tr>
		@endforeach
		</tbody>
		</table>
	@endif
	@can('update', $database)
			<livewire:datasetdef-form :database=$database />
		@if(count($database->datasets) > 0)
			<p><b>Note:</b> The definition can be expanded but not shortened because the database contains datasets already.</p>
		@endif
	@endcan
</x-app-layout>
