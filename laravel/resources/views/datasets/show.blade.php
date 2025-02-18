<x-app-layout>
    <x-slot name="header">
        <x-database.header :dataset="$dataset" />
    </x-slot>
    <h2>
        Dataset: {{ $dataset->name }} @role('admin')
            (ID: {{ $dataset->id }})
        @endrole
    </h2>
    <h3>Files</h3>
    <p>This dataset contains {{ count($dataset->datafiles) }} files</p>
    {{-- <p>This dataset contains <livewire:dataset.datafile-counter :dataset="$dataset" /> files.</p> --}}

    <div>
        @foreach ($dataset->datafiles as $datafile)
            <div wire:key="{{ $datafile->id }}">
                @if (!$datafile->isImage())
                    {{-- @livewire(DatafileListener::class, ['datafile' => $datafile]) --}}
                    <x-property name="File">
                        <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a>
                    </x-property>
                    <livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
                    @can('delete', $datafile)
                        <x-button method="DELETE" action="{{ route('datafiles.destroy', [$datafile]) }}">
                            Delete
                        </x-button>
                    @endcan
                @else
                    <div>
                        <x-property name="File">
                            <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a>
                        </x-property>
                        <x-img class="p-2 " asset="{{ $datafile->asset() }}" />
                        @can('delete', $datafile)
                            <x-button method="DELETE" action="{{ route('datafiles.destroy', [$datafile]) }}">
                                Delete
                            </x-button>
                        @endcan
                    </div>
                @endif
            </div>
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
    {{-- display option to upload whole folder, if there are not datafiles yet --}}
    {{-- jw:note - not implemented yet
    @can('update', $dataset->database)
        @if (count($dataset->datafiles) == 0)
            <p>Upload all files from a folder</p>
            <livewire:dataset-upload :dataset=$dataset />
        @endif
    @endcan
	--}}
    {{-- jw:note do *not* use a livewire inside a livewire if possible --}}
    {{-- @livewire(DatasetListener::class, ['dataset' => $dataset]) --}}
    {{--    <livewire:DatasetListener :dataset="$dataset" /> --}}

</x-app-layout>
