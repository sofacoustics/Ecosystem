<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Datafile: {{ $datafile->name }}
        </h2>
        Dataset: <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a><br>
        Database: <a href="{{ route('databases.show', $datafile->dataset->database->id) }}">{{ $datafile->dataset->database->name }}</a>
    </x-slot>

    <p><a href="{{ asset($datafile->url()) }}">Download</a></p>

    {{-- <p>jw:todo maybe just pass the $datafile and let the component decide if it is an image or not?</p> --}}

    @livewire(DatafileListener::class, ['datafile' => $datafile])

    {{-- <x-img :datafile="$datafile" :asset="$datafile->asset()"/> --}}

{{-- <p>jw:todo display stuff for this file. Via a component, so we can do it based on the file type?</p> --}}
</x-app-layout>
