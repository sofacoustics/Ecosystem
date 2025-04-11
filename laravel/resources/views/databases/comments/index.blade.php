<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>New comment:</h3>

    <livewire:comment-form :database=$database />

</x-app-layout>
