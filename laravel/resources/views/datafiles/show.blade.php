<x-app-layout>
    <x-slot name="header">
        <x-slot name="header">
            <x-database.header :datafile="$datafile" />
        </x-slot>

        <x-property name="Dataset">
            <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a><br>
        </x-property>
        <x-property name="Datafile name">
            {{ $datafile->name }}
        </x-property>
        <x-property name="Datafiletype">
            {{ $datafile->datasetdef->datafiletype->name }}<br>
        </x-property>

        {{-- <p>jw:todo maybe just pass the $datafile and let the component decide if it is an image or not?</p> --}}

        {{-- --}}
        @livewire(DatafileListener::class, ['datafile' => $datafile])

        {{-- <x-img :datafile="$datafile" :asset="$datafile->asset()"/> --}}

        <p><a href="{{ asset($datafile->url()) }}" download>Download</a></p>
        {{-- <p>jw:todo display stuff for this file. Via a component, so we can do it based on the file type?</p> --}}
</x-app-layout>
