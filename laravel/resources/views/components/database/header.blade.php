{{--
    the database header to put in the app layout slot 'header'

    Parameters

        $database   The database
        $dataset    The dataset ($database does not need to be set)
--}}
@php
    if (isset($datafile)) {
        $dataset = $datafile->dataset;
    }
    if (isset($dataset)) {
        $database = $dataset->database;
    }
@endphp
<h2 class="font-semibold text-xl text-gray-800 leading-tight">Title:
    <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
    {{-- https://spatie.be/docs/laravel-permission/v6/basic-usage/blade-directives --}}
    @role('admin')
        (ID: {{ $database->id }})
    @endrole
    @can('update', $database)
        <x-button method="GET" action="{{ route('databases.edit', [$database]) }}" class="inline">
            Edit
        </x-button>
    @endcan
</h2>
<x-property name="Ecosystem Owner">
    {{ \App\Models\User::find($database->user_id)->name }}
</x-property>
<x-property name="Description">
    {{ $database->description }}
</x-property>
<a class="btn btn-primary" href="{{ route('databases.show', $database->id) }}">Datasets</a>
<a class="btn btn-primary" href="{{ route('databases.datasetdefs', $database->id) }}">Datasetdef</a>
<a class="btn btn-primary" href="{{ route('databases.creators', $database->id) }}">Creators</a>
