<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Dataset: {{ $dataset->title }}
        </h2>
    </x-slot>
    <h1>Title: {{ $dataset->title }}</h1>
    <p>Description: {{ $dataset->description }}</p>
    <p>Uploaded by: {{ $user->name }}<br>
    Created: {{ $dataset->created_at }}<br>
    Updated: {{ $dataset->updated_at }}</p>
    {{ $dataset }}
</x-app-layout>