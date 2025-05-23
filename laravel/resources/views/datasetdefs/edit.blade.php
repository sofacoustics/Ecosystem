{{--
    Edit a single dataset definition
--}}
<x-app-layout>
    <x-slot name="header">
        <x-database.header :database="$datasetdef->database" />
    </x-slot>
    <div>
        <livewire:datasetdef-form :database="$datasetdef->database" :datasetdef=$datasetdef />
    </div>
		<!-- <p>resources\views\datasetdefs\edit.blade.php</p> --!>
</x-app-layout>
