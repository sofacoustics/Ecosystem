<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Local Datasets
        </h2>
    </x-slot>
    <p>The following local datasets are available</p>

    @foreach ($allDatasets as $dataset)
        <p><a class="underline" href="/dataset/{{ $dataset->id }}">{{ $dataset->title }}</a></p>
    @endforeach
</x-app-layout>

