<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Datasetdefs
        </h2>
    </x-slot>

    <ul class="list-discs">
    @foreach($allDatasetdefs as $datasetdef)
        <li><x-datasetdef.overview :datasetdef=$datasetdef/></li>

    @endforeach
    </ul>
    <p>file: views/datasetdefs/index.blade.php</p>
</x-app-layout>





