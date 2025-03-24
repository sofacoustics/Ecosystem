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
    <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }} ({{ $database->productionyear }})</a>
    @role('admin')
			<small>(ID: {{ $database->id }})</small>
    @endrole
</h2>

<x-property name="Additional Title">
  {{ $database->additionaltitle }}
</x-property>

<x-property name="Ecosystem Manager">
  {{ \App\Models\User::find($database->user_id)->name }}
</x-property>

@can('update', $database)
		<x-button method="GET" action="{{ route('databases.edit', [$database]) }}" class="inline">Edit</x-button>
@endcan

<x-button class="inline" method="GET" action="{{ route('databases.datasetdefs', $database->id) }}">Definition</x-button>

@if(count($database->datasetdefs))
	<x-button class="inline" method="GET" action="{{ route('databases.show', $database->id) }}">Datasets</x-button>
@endif

@can('create', [App\Models\Dataset::class, $database])
	@if(count($database->datasetdefs))
		<x-button method="GET" class="inline" action="{{ route('databases.datasets.create', [$database->id]) }}">Upload</x-button>
		<x-button method="GET" class="inline" action="{{ route('databases.datasets.bulkupload', [$database->id]) }}">Bulk Upload</x-button>
	@endif
@endcan

@can('update', $database)

	<?php /*@if($database->published)

		<form action="{{route('databases.publish')}}" method="post">
				<div>
						<button type="submit" class="btn btn-danger">Publish</button>
				</div>
		</form>
		<?php /*<x-button method="GET" action="{{ route('databases.hide', [$database]) }}" class="inline">Hide</x-button>  
	@else
		<x-button method="GET" action="{{ route('databases.publish', [$database]) }}" class="inline">Publish</x-button> 
	@endif */ ?>
@endcan
