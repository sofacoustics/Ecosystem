<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Definition of a Dataset:</h3>
    <ul class="list-disc list-inside">
        @foreach($database->datasetdefs as $datasetdef)
            <li class="list-disc list-inside"><x-datasetdef.show :datasetdef=$datasetdef /></li>
        @endforeach
    </ul>
    {{-- include a form to create a new datasetdef --}}
    @can('create', App\Models\Datasetdef::class)
        @if(count($database->datasets) == 0)
            <livewire:datasetdef-form :database=$database />
        @else
            <p>The dataset definition is locked because the database contains datasets.</p>
        @endif
    @endcan
		<p>resources\views\databases\datasetdefs\index.blade.php</p>
</x-app-layout>
