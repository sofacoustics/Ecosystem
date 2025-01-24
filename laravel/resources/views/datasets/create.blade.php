<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
        Database: <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
        </h2>
    </x-slot>
    <div>
        <p>resources/views/datasets/create.blade.php</p>
        <livewire:dataset-form :database=$database />
    </div>
</x-app-layout>
