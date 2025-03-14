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

<!-- <p>laravel\resources\views\components\database\header.blade.php</p> --!>

<h2 class="font-semibold text-xl text-gray-800 leading-tight">
    <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
    @role('admin')
			(ID: {{ $database->id }})
    @endrole
</h2>
<x-property name="Description">
    {{ $database->description }}
</x-property>
<x-property name="Ecosystem Manager">
    {{ \App\Models\User::find($database->user_id)->name }}
</x-property>
    @can('update', $database)
        <x-button method="GET" action="{{ route('databases.edit', [$database]) }}" class="inline">Edit Metadata</x-button>
    @endcan
<x-button class="inline" method="GET" action="{{ route('databases.show', $database->id) }}">Datasets</x-button>
<x-button class="inline" method="GET" action="{{ route('databases.datasetdefs', $database->id) }}">Definition</x-button>

