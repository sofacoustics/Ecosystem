<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dataset: {{ $dataset->name }}
        </h2>
        Database: <a href="{{ route('databases.show', $dataset->database->id) }}">{{ $dataset->database->name }}</a>
    </x-slot>
    <p>This dataset contains {{ count($dataset->datafiles) }} datafiles</p>
    {{--
    <div class="flex flex-row">
        @forelse ($dataset->datafiles as $datafile)
            <a class="px-5" href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a><br/>
        @empty
            <p>There are no datasets associated with this datafile.</p>
        @endforelse
    </div> --}}
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
            @endif
        @endforeach
    </div>
    {{-- jw:note do *not* use a livewire inside a livewire if possible --}}
    {{-- @livewire(DatasetListener::class, ['dataset' => $dataset]) --}}
    {{--    <livewire:DatasetListener :dataset="$dataset" /> --}}

</x-app-layout>
