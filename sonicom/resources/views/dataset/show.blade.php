<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Dataset
        </h2>
    </x-slot>
    <h1>Title: {{ $dataset->title }}</h1>
    <p>Uploaded by: {{ $user->name }}</p>
    {{ $dataset }}
</x-app-layout>