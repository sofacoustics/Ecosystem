<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Datasets
        </h2>
    </x-slot>
    <p>The following datasets are available</p>
    <ul>
    @if (isset($datasets->data))
        @foreach ($datasets->data as $dataset)
        <li> {{ $dataset->descriptiveMetadata->title }} </li>
        @endforeach
    @else
        <p>There are not datasets in this workspace!</p>    
    @endif
    </ul>
</x-app-layout>