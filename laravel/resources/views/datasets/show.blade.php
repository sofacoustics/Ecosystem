<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dataset {{ $dataset->name }}
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
                File: {{ $datafile->name }}
                <livewire:DatafileListener :datafile="$datafile" />
            @endif
        @endforeach
    
        <div class="flex flex-row">
        @foreach ($dataset->datafiles as $datafile)
            @if ($datafile->isImage())
                <x-img caption="{{ $datafile->name }}" class="p-2" asset="{{ $datafile->asset() }}" />
            @endif
        @endforeach
        </div>
    </div>
    {{-- jw:note do *not* use a livewire inside a livewire if possible --}}
    {{-- @livewire(DatasetListener::class, ['dataset' => $dataset]) --}}
    {{--    <livewire:DatasetListener :dataset="$dataset" /> --}}

</x-app-layout>
