<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>
	<h3>Each dataset is defined as followed:</h3>
	<ul class="list-disc list-inside">
		@foreach($database->datasetdefs as $datasetdef)
			<?php /*<li class="list-disc list-inside"><x-datasetdef.show :datasetdef=$datasetdef /></li> */ ?>
			<li>
				@can('delete', [$datasetdef, $database])
					<x-button method="DELETE" class="inline" action="{{ route('datasetdefs.destroy', [$datasetdef]) }}">Delete</x-button>
				@endcan
				@can('update', [$datasetdef, $database])
					<x-button method="GET" class="inline" action="{{ route('datasetdefs.edit', [$datasetdef]) }}">Edit</x-button>
				@endcan
				<b>Name:</b> {{ $datasetdef->name }}, <b>Type:</b> {{ $datasetdef->datafiletype->name }}
				@if($datasetdef->widget), <b>Linked Widget:</b> {{ $datasetdef->widget->name }} @endif
				@role('admin') (ID: {{ $datasetdef->datafiletype->id }}), @endrole
			</li>
		@endforeach
	</ul>

	@can('update', $database)
		@if(count($database->datasets) == 0)
				<livewire:datasetdef-form :database=$database />
		@else
				<p>Note: The definition cannot be expanded or shortened because the database contains datasets already.</p>
		@endif
	@endcan
		{{-- <p>resources\views\databases\datasetdefs\index.blade.php</p> --}}
</x-app-layout>
