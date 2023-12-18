<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Database: {{ $database->title }}
        </h2>
    </x-slot>
    <h1>Title: {{ $database->title }}</h1>
    <h2>Description: {{ $database->description }}</h2>
    <p>Datasets</p>

    @forelse($database->datasets as $dataset)
        - {{ $dataset->name }}<br>
    @empty
        <p>There are no datasets associated with this database</p>
    @endforelse


    <div class="text-xs">
    <p>Uploaded by: {{ $user->name }}<br>
    Created: {{ $database->created_at }}<br>
    Updated: {{ $database->updated_at }}</p>
    {{ $database }}
</div>
</x-app-layout>