<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database tabTitle='Definition'/>
	</x-slot>
	@if(count($database->datasetdefs)>0)		
		<h3>Each dataset is defined as followed:</h3>
		<table class="table-auto border border-slate-399">
		<thead class="bg-gray-50">
			<tr>
				<x-th>#</x-th>
				@if($edits)
					<x-th colspan="5">Commands</x-th>
				@else
					<x-th colspan="5"></x-th>
				@endif
				<x-th>Name</x-th>
				<x-th>Type</x-th>
				<x-th>Widget</x-th>
				<x-th># Datafiles</x-th>
				<x-th>Description</x-th>
				@role('admin')
					<x-th>ID</x-th>
					<x-th title="You can rerun this service for *all* datafiles using this dataset definition">Rerun service</x-th>
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
				<x-td>{{ $datasetdef->name }}</x-td>
				<x-td>{{ $datasetdef->datafiletype->name }}</x-td>
				<x-td>{{ $datasetdef->widget->name ?? 'No widget' }}</x-td>
				<x-td>{{ count($datasetdef->datafiles) }}</x-td>
				<x-td>{{ $datasetdef->description }}</x-td>
				@role('admin') 
					<x-td>{{ $datasetdef->datafiletype->id }}</x-td>
					<x-td><x-button method="POST" action="{{ route('datasetdefs.rerunservice', [$datasetdef]) }}" class="inline">Rerun service</x-button></x-td>
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
