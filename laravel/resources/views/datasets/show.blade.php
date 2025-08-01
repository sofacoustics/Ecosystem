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
		@hasrole('admin')
			<x-button method="POST" class="inline" action="{{ route('datafiles.touch', [$datafile]) }}">Rerun service</x-button>
		@endhasrole
		<div class="ml-2" wire:key="{{ $datafile->id }}">
			<x-property name="Datafile Type">
				{{ $datafile->datasetdef->datafiletype->name }}
			</x-property>
			<x-property name="Datafile Name">
				<a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a>@hasrole('admin') (ID: {{ $datafile->id }})@endhasrole
			</x-property>
			@hasrole('admin')
				@if($datafile->radar_id)
					<x-property name="RADAR id">{{ $datafile->radar_id}}</x-property>
				@endif
			@endhasrole
			<x-property name="Updated at">
				{{ $datafile->updated_at }} (GMT)
			</x-property>
			<x-property name="Created at">
				{{ $datafile->created_at }} (GMT)
			</x-property>			
			<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
		</div>

		<br><br><hr>
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
