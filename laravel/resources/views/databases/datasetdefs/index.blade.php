<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Datasetdef</h3>
    <p>The following {{ count($database->datasetdefs) }} dataset definitions exist for the database "{{ $database->title }}"</p>

    <ul class="list-disc list-inside">
        @foreach($database->datasetdefs as $datasetdef)
            <x-datasetdef.show :datasetdef=$datasetdef />
        @endforeach
    </ul>
    {{-- include a form to create a new datasetdef --}}
    @can('create', App\Models\Datasetdef::class)
        @if(count($database->datasets) == 0)
            <livewire:datasetdef-form :database=$database />
        @else
            <p>Since there are datasets in this database, the dataset definitions may not be altered.</p>
        @endif
    @endcan
</x-app-layout>
