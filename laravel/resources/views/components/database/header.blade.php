{{-- 
    the database header to put in the app layout slot 'header'

    Parameters

        $database   The database
        $dataset    The dataset ($database does not need to be set)
--}}
@php
    if(isset($dataset))
        $database = $dataset->database;
@endphp
<h2 class="font-semibold text-xl text-gray-800 leading-tight">Title: 
	<a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a></h2>
<b>Ecosystem Owner:</b> {{ \App\Models\User::find($database->user_id)->name }}<br>
<b>Description:</b> {{ $database->description }}<br>
<a class="btn btn-primary" href="{{ route("databases.show", $database->id) }}">Datasets</a>
<a class="btn btn-primary" href="{{ route("databases.datasetdefs", $database->id) }}">Datasetdef</a>
<a class="btn btn-primary" href="{{ route("databases.creators", $database->id) }}">Creators</a>

