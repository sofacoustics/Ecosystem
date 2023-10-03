<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Dataset: {{ $dataset->title }}
        </h2>
    </x-slot>
    <h1>Title: {{ $dataset->title }}</h1>
    <h2>Description: {{ $dataset->description }}</h2>
    <p>Files</p>

    @forelse($dataset->files as $file)
        - {{ $file->name }}<br>
    @empty
        <p>There are no files associated with this dataset</p>
    @endforelse


    <div class="text-xs">
    <p>Uploaded by: {{ $user->name }}<br>
    Created: {{ $dataset->created_at }}<br>
    Updated: {{ $dataset->updated_at }}</p>
    {{ $dataset }}
</div>
</x-app-layout>