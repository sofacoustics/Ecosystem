<x-app-layout>
    <x-slot name="header">
        <x-database.header :dataset="$dataset" />
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
                {{-- <div wire:key="{{ $missing->id }}"> --}}
                <li>
                    <x-datasetdef.list :datasetdef="$missing" /> missing-id={{ $missing->id }}
                    <livewire:datafile-upload :dataset=$dataset :datasetdef=$missing :wire:key="$missing->id" />
                </li>
                {{-- </div> --}}
            @endforeach
        </ul>
    @endif

</x-app-layout>
