<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>

    @foreach($database->datasetdefs as $datasetdef)
        <div>
            <p>id: {{ $datasetdef->id }}, name: {{ $datasetdef->name }}, type: {{ $datasetdef->datafiletype->name }}</p>
        </div>
    @endforeach

</x-app-layout>
