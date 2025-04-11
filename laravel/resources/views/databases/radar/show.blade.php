<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>RADAR Metadata</h3>
    <x-radar.dataset :dataset="$database->radardataset">
        A radar.div component with a dataset parameter
    </x-radar.dataset>
</x-app-layout>

