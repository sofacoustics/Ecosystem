<x-app-layout>
    <x-slot name="header">
        <x-tool.header :tool=$tool />
    </x-slot>
    <h3>Tools</h3>

    @forelse($tool->datasets as $dataset)
        - <a href={{ route('datasets.show', $dataset->id) }}>{{ $dataset->name }}</a> <br>
    @empty
        <p>There are no datasets associated with this database</p>
    @endforelse


@env('local')

    <ul class="list-disc list-inside">
    </div>
@endenv

</x-app-layout>
