{{--
	the database header to put in the app layout slot 'header'

	Parameters

		$database   The database
		$dataset    The dataset ($database does not need to be set)
		$datafile   The datafile ($database does not need to be set)
		$tabTitle	The title for this tab, e.g. Database | Datasets
--}}
<?php
	// use $dataset->database, if $database is not set
	if(isset($dataset) && !isset($database))
		$database = $dataset->database;
	if(isset($datafile) && !isset($database))
		$database = $datafile->dataset->database;

?>

{{-- Set the HTML <title> here, overriding the default, which is defined in app.blade.php --}}
@if(isset($pageTitle))
	@section('pageTitle', "$pageTitle")
@else
	@section('pageTitle', "Database: $database->title")
@endif
@if(isset($tabTitle))
	@section('tabTitle', " | " . $tabTitle)
@endif

<style>
	.container {
		display: flex; /* Make this the flex container for the columns */
		flex-grow: 1; /* Allow container to fill available vertical space */
		flex-wrap: wrap; /* Allows columns to wrap on smaller screens */
	}

	.column {
		flex: 1; /* Each column takes equal available space */
		min-width: 300px; /* Optional: Minimum width before wrapping */
		padding: 20px;
		box-sizing: border-box; /* Include padding in element's total width/height */
		display: flex; /* Optional: If content inside needs flex layout */
		flex-direction: column; /* Optional: If content inside needs flex layout */
	}

	.column.left {
	}

	.column.right {
		text-align: right;
	}

	/* Responsive Adjustments */
	@media (max-width: 768px) {
		.container {
			flex-direction: column; /* Stack columns vertically on small screens */
		}
		.column {
			flex: none; /* Remove flex-grow on small screens */
			width: 100%; /* Take full width */
		}
	}
</style>


<h2 class="font-semibold text-xl text-gray-800 leading-tight">Database:
	<a href="{{ route('databases.show', $database->id) }}">{{ $database->title }}</a>
	@if($database->productionyear!="unknown")
		({{ $database->productionyear }})
	@endif
</h2>

<p>
	@if($database->additionaltitle)
		<x-property name="Subtitle">
			{{ $database->additionaltitle }}
		</x-property>
	@endif

	@if($database->doi)
		<x-property name="DOI">
			@if($database->radar_status==4)
				<a href="https://doi.org/{{ $database->doi }}">{{ $database->doi }}</a>
			@else
				{{ $database->doi }}
			@endif
			<img id="copyDOI" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy DOI to Clipboard" style="height: 1.5em; display: inline-block;"><input type="text" id="textDOI" value="{{ $database->doi }}" class="hidden">
		</x-property>
	@endif

	<x-property name="Cite as" title="{{ $database->citationtooltip() }}">
		{!! $database->citation() !!}
		<img id="copyCitation" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy Citation to Clipboard" style="height: 1.5em; display: inline-block;"><input type="text" id="textCitation" value="{{ strip_tags($database->citation()) }}" class="hidden">
	</x-property>
</p>

<p>
	<x-button class="inline" method="GET" action="{{ route('databases.datasetdefs', $database->id) }}">Definition</x-button>

	@if(count($database->datasetdefs))
		<x-button class="inline" method="GET" action="{{ route('databases.showdatasets', $database->id) }}">Datasets</x-button>
	@endif

	@if(count($database->datasets))
		<x-button class="inline" method="GET" action="{{ route('databases.download', $database->id) }}">Downloads</x-button>
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
			@if (count($database->datasets))
				<x-button method="GET" action="{{ route('databases.purge', [$database->id]) }}" class="inline">Purge Database</x-button>
			@endif
		@endcan
		@can('delete', $database)
			<x-button method="DELETE" action="{{ route('databases.destroy', [$database->id]) }}" class="inline"
				confirm="Are you sure to delete the Database?">
				Delete Database
			</x-button>
		@endcan
	@endcan
	
	@hasrole('admin')
		<x-button method="GET" action="{{ route('databases.datathek', [$database->id]) }}" class="inline">Datathek</x-button>
	@endhasrole
</p>

<script>
	document.getElementById('copyDOI')?.addEventListener('click', function() {
			// Get the text from the input field
		var textToCopy = document.getElementById('textDOI').value;

		// Use the Clipboard API to copy the text
		navigator.clipboard.writeText(textToCopy).then(function() {
				alert(textToCopy + '\ncopied to the clipboard...');
		}).catch(function(err) {
				console.error('Failed to copy text: ', err);
				alert('Failed to copy text. Please copy manually.'); // Inform the user
		});
	});
	
	document.getElementById('copyCitation')?.addEventListener('click', function() {
			// Get the text from the input field
		var textToCopy = document.getElementById('textCitation').value;

		// Use the Clipboard API to copy the text
		navigator.clipboard.writeText(textToCopy).then(function() {
				alert(textToCopy + '\ncopied to the clipboard...');
		}).catch(function(err) {
				console.error('Failed to copy text: ', err);
				alert('Failed to copy text. Please copy manually.'); // Inform the user
		});
	});
	
</script>
