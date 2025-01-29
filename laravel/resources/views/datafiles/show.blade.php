<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Datafile: {{ $datafile->name }}
        </h2>
        Dataset: <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a><br>
        Database: <a href="{{ route('databases.show', $datafile->dataset->database->id) }}">{{ $datafile->dataset->database->name }}</a><br>
        Datafiletype: {{ $datafile->datasetdef->datafiletype->name }}<br>
        {{--_Widget: {{ $datafile->datasetdef->widget->name }} --}}
    </x-slot>

    {{-- <p>jw:todo maybe just pass the $datafile and let the component decide if it is an image or not?</p> --}}

    {{-- --}}
    @livewire(DatafileListener::class, ['datafile' => $datafile])

    {{-- <x-img :datafile="$datafile" :asset="$datafile->asset()"/> --}}

    <p><a href="{{ asset($datafile->url()) }}" download>Download</a></p>
{{-- <p>jw:todo display stuff for this file. Via a component, so we can do it based on the file type?</p> --}}
</x-app-layout>
