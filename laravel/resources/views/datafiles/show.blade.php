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

        {{-- call view based on datafiletype --}}
        @livewire(DatafileListener::class, ['datafile' => $datafile])

        <p><a href="{{ asset($datafile->url()) }}" download>Download</a></p>
</x-app-layout>
