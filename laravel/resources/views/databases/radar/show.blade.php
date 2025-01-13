<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database: {{ $database->title }}<br>
        </h2>
        User: {{ \App\Models\User::find($database->user_id)->name }}
        @auth
        @endauth<br>
        Description: {{ $database->description }}
    </x-slot>
    <h3>RADAR Metadata</h3>
    <x-radar.dataset :dataset="$database->radardataset">
        A radar.div component with a dataset parameter
    </x-radar.dataset>
</x-app-layout>

