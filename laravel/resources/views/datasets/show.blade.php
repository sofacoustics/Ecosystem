<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Dataset {{ $dataset->name }}
        </h2>
        Database: <a href="{{ route('databases.show', $dataset->database->id) }}">{{ $dataset->database->name }}</a>



    </x-slot>
    <p>This dataset contains {{ count($dataset->datafiles) }} datafiles</p>
    @forelse($dataset->datafiles as $datafile)
        - <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a><br/>
    @empty
        <p>There are no datasets associated with this datafile.</p>
    @endforelse


 
</x-app-layout>