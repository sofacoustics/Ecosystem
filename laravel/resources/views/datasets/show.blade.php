<x-app-layout>
    <x-slot name="header">
        <x-database.header :dataset="$dataset" />
    </x-slot>
    <h2>
        Dataset: {{ $dataset->name }} @role('admin') (ID: {{ $dataset->id }}) @endrole
    </h2>
    <h3>Files</h3>
    <p>This dataset contains {{ count($dataset->datafiles) }} files</p>
    <div>
        @foreach ($dataset->datafiles as $datafile)
            @if (!$datafile->isImage())
                {{-- @livewire(DatafileListener::class, ['datafile' => $datafile]) --}}
                <a href="{{ route('datafiles.show', $datafile->id) }}">File: {{ $datafile->name }}</a><br/>
                <livewire:DatafileListener :datafile="$datafile" />
            @endif
        @endforeach

        <!-- ear left, ear right -->
        @foreach ($dataset->datafiles as $datafile)
            @if ($datafile->isImage())
                <a href="{{ route('datafiles.show', $datafile->id) }}">File: {{ $datafile->name }}</a><br/>
                <x-img class="p-2 " asset="{{ $datafile->asset() }}" />
                    <p> {{ route('datafiles.destroy', [$datafile]) }} }} </p>
                <form class="bg-red-100 inline" method="POST" action="{{ route('datafiles.destroy', [$datafile]) }}">
                        @csrf @method('DELETE')
                        (<button type="submit" class="btn btn-danger btn-sm">Delete</button>)
                </form>

            @endif
        @endforeach
    </div>
    @if(count($dataset->datafiles) < count($dataset->database->datasetdefs))
        <p>According to the dataset definition, the following {{ (count($dataset->database->datasetdefs) - count($dataset->datafiles)) }} files are missing:</p>

        <ul class="list-disc list-inside">
        @foreach($dataset->missing_datafiles() as $missing)
            <li>
                <x-datasetdef.list :datasetdef="$missing" /> 
                <livewire:datafile-upload :dataset=$dataset :datasetdef=$missing />
            </li>
        @endforeach
        </ul>
    @endif

    {{-- jw:note do *not* use a livewire inside a livewire if possible --}}
    {{-- @livewire(DatasetListener::class, ['dataset' => $dataset]) --}}
    {{--    <livewire:DatasetListener :dataset="$dataset" /> --}}

</x-app-layout>
