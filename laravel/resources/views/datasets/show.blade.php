<x-app-layout>
    <x-slot name="header">
		<x-database.header :dataset="$dataset" tabTitle="Dataset | {{ $dataset->name }}"/>
    </x-slot>
    <h2>
        Dataset: {{ $dataset->name }} @role('admin')
            (ID: {{ $dataset->id }})
        @endrole
    </h2>
    <p>This dataset contains {{ count($dataset->datafiles) }} files.</p>
    <div class="ml-2">
			@foreach ($dataset->datafiles as $datafile)
				<h3>Name: {{ $datafile->datasetdef->name }}
					@can('delete', $datafile)
						<x-button method="DELETE" class="inline" action="{{ route('datafiles.destroy', [$datafile]) }}">Delete</x-button>
					@endcan
				</h3>
				<div wire:key="{{ $datafile->id }}">
					<x-property name="Datafile Name">
						<a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a> @role('admin') (ID: {{ $datafile->id }}) @endrole
					</x-property>
					<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
				</div>
				<hr>
			@endforeach
    </div>

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

	@can('update', $dataset)
		@if($dataset->radar_id == null)
			<x-button loadingText="Uploading: please be patient" method="POST" action="{{ route('datasets.uploadtoradar', $dataset) }}">Upload to RADAR</x-button>
		@else
			<x-button loadingText="Deleting: please be patient" method="DELETE" action="{{ route('datasets.deletefromradar', $dataset) }}">Delete from RADAR</x-button>
		@endif
	@endif

</x-app-layout>
