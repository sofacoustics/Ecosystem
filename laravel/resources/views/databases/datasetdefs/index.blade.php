<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Each dataset is defined as followed:</h3>
    <ul class="list-disc list-inside">
        @foreach($database->datasetdefs as $datasetdef)
            <li class="list-disc list-inside"><x-datasetdef.show :datasetdef=$datasetdef /></li>
        @endforeach
    </ul>

    @can('update', $database)
        @if(count($database->datasets) == 0)
            <livewire:datasetdef-form :database=$database />
        @else
            <p>Note: The definition cannot be expanded or shortened because the database contains datasets already.</p>
        @endif
    @endcan
		{{-- <p>resources\views\databases\datasetdefs\index.blade.php</p> --}}
</x-app-layout>
