<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Datasetdef</h3>
    <p>The {{ $database->title }} dataset definition (datasetdef) contains {{ count($database->datasetdefs) }} files. Each dataset must consist of one exemplar of each of these files.</p>

    <ul class="list-disc list-inside">
    @foreach($database->datasetdefs as $datasetdef)
            <li>{{ $datasetdef->name }}, type: {{ $datasetdef->datafiletype->name }}</li>
    @endforeach
    </ul>

</x-app-layout>
