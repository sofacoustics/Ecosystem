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
					<th class="border p-2">Edit</th>
				@endif
				@if($deletes)
					<th class="border p-2">Delete</th>
				@endif
				<th class="border p-2">Name</th>
				<th class="border p-2">Type</th>
				<th class="border p-2">Widget</th>
				<th class="border p-2">Description</th>
				@role('admin')
					<th class="border p-2">ID</th>
				@endrole
			</tr>
		</thead>

		<tbody class="bg-white divide-y divide-gray-200">
		@foreach($database->datasetdefs as $datasetdef)
			<tr>
				<td class="border p-2">{{ ($loop->index)+1 }}</td>
				@can('update', [$datasetdef, $database])
					<td class="border p-2">
						<x-button method="GET" class="inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">Edit</x-button>
					</td>
				@endcan
				@can('delete', [$datasetdef, $database])
					<td class="border p-2">
						<x-button method="DELETE" class="inline" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">Delete</x-button>
					</td>
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
		@if(count($database->datasets) == 0)
			<livewire:datasetdef-form :database=$database />
		@else
			<p><b>Note:</b> The definition cannot be expanded or shortened because the database contains datasets already.</p>
		@endif
	@endcan
</x-app-layout>
