<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dataset {{ $dataset->name }}
        </h2>
        Database: {{ $dataset->database->name }}
    </x-slot>
    <p>This dataset contains {{ count($dataset->datafiles) }} datafiles</p>
    @forelse($dataset->datafiles as $datafile)
        - <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a><br/>
    @empty
        <p>There are no datasets associated with this datafile.</p>
    @endforelse


   {{-- <h2>Database: {{ $database->name }}</h2> --}}

</x-app-layout>