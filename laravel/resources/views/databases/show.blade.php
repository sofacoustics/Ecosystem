<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Database: {{ $database->title }}<br>
        </h2>
        Description: {{ $database->description }}
    </x-slot>
    <p>Datasets</p>

    @forelse($database->datasets as $dataset)
        - <a href={{ route('datasets.show', $dataset->id) }}>{{ $dataset->name }}</a> <br>
    @empty
        <p>There are no datasets associated with this database</p>
    @endforelse


    <div class="text-xs">
    <p>Uploaded by: {{ $user->name }}<br>
    Created: {{ $database->created_at }}<br>
    Updated: {{ $database->updated_at }}</p>
    {{-- $database --}}
</div>
</x-app-layout>
