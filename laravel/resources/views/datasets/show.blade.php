<x-app-layout>
	<x-slot name="header">
	<x-database.header :dataset="$dataset" tabTitle="Dataset | {{ $dataset->name }}"/>
	</x-slot>
	<h2>
		Dataset name: {{ $dataset->name }} @role('admin')
			(ID: {{ $dataset->id }})
		@endrole
	</h2>
	@foreach ($dataset->datafiles as $datafile)
		@if(count($dataset->datafiles) == 1)
			<h3>Datafile: {{ $datafile->datasetdef->name }}</h3>
		@else
			<h3>Datafile #{{$loop->index+1}}: {{ $datafile->datasetdef->name }}</h3>
		@endif
		@can('delete', $datafile)
			<x-button method="DELETE" class="inline" action="{{ route('datafiles.destroy', [$datafile]) }}">Delete</x-button>
		@endcan
		<div class="ml-2" wire:key="{{ $datafile->id }}">
			<x-property name="Datafile Type">
				{{ $datafile->datasetdef->datafiletype->name }}
			</x-property>
			<x-property name="Datafile Name">
				<a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a>
			</x-property>
			<x-property name="Updated at">
				<a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->updated_at }} (GMT)</a> 
			</x-property>
			<x-property name="Created at">
				<a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->created_at }} (GMT)</a> 
			</x-property>			
			@hasrole('admin')
				<x-button method="POST" action="{{ route('datafiles.touch', [$datafile]) }}">Rerun service</x-button>
			@endhasrole
			<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
		</div>
		<hr>
	@endforeach

	@if (count($dataset->datafiles) < count($dataset->database->datasetdefs))
		<p>According to the dataset definition, the following
			{{ count($dataset->database->datasetdefs) - count($dataset->datafiles) }} files are missing:</p>

		<ul class="list-disc list-inside">
			@foreach ($dataset->missing_datafiles() as $missing)
				<li>
					<x-datasetdef.list :datasetdef="$missing" />
					<livewire:datafile-upload :dataset=$dataset :datasetdef=$missing :wire:key="$missing->id" />
				</li>
			@endforeach
		</ul>
	@endif

</x-app-layout>
