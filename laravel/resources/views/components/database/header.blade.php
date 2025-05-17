{{--
	the database header to put in the app layout slot 'header'

	Parameters

		$database   The database
		$dataset    The dataset ($database does not need to be set)
--}}

<h2 class="font-semibold text-xl text-gray-800 leading-tight">Database: 
	<a href="{{ route('databases.show', $database->id) }}">{{ $database->title }} ({{ $database->productionyear }})</a>
	@role('admin')
			<small>(ID: {{ $database->id }})</small>
	@endrole
</h2>

<x-property name="Additional Title">
  {{ $database->additionaltitle }}
</x-property>

<x-property name="Manager at the Ecosystem">
  {{ \App\Models\User::find($database->user_id)->name }}
</x-property>

<x-button class="inline" method="GET" action="{{ route('databases.datasetdefs', $database->id) }}">Definition</x-button>

@if(count($database->datasetdefs))
	<x-button class="inline" method="GET" action="{{ route('databases.showdatasets', $database->id) }}">Datasets</x-button>
@endif

@if(count($database->datasets))
	<x-button class="inline" method="GET" action="{{ route('databases.download', $database->id) }}">Download</x-button>
@endif

@can('update', $database)
	@if(count($database->datasetdefs))
		<x-button method="GET" class="inline" action="{{ route('databases.datasets.create', [$database->id]) }}">Upload</x-button>
		<x-button method="GET" class="inline" action="{{ route('databases.upload', [$database->id]) }}">Bulk Upload</x-button>
	@endif
@endcan

@can('own', $database)
	<x-button method="GET" action="{{ route('databases.visibility', [$database]) }}" class="inline">Manage Visibility</x-button>
	@can('update', $database)
		<x-button method="GET" action="{{ route('databases.edit', [$database]) }}" class="inline">Edit Metadata</x-button>
		@if (count($database->datasets))
			<x-button method="GET" action="{{ route('databases.purge', [$database->id]) }}" class="inline">Purge Database</x-button>
		@endif
	@endcan
	@can('delete', $database)
		<x-button method="DELETE" action="{{ route('databases.destroy', [$database->id]) }}" class="inline">Delete Database</x-button>
	@endcan
	<?php /* <x-button method="GET" action="{{ route('databases.publish', [$database]) }}" class="inline">Publish to RADAR</x-button> */ ?>
@endcan
