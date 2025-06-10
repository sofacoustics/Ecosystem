{{-- the Tool header to put in the app layout slot 'header' --}}

{{-- Set the HTML <title> here, overriding the default, which is defined in app.blade.php --}}
@if(isset($pageTitle))
	@section('pageTitle', "$pageTitle")
@else
	@section('pageTitle', "Tool: $tool->title")
@endif
@if(isset($tabTitle))
	@section('tabTitle', " | " . $tabTitle)
@endif

<h2 class="font-semibold text-xl text-gray-800 leading-tight">Tool:
	<a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a> 
	@if($tool->productionyear!="unknown")
		({{ $tool->productionyear }})
	@endif
</h2>

<p>
	@if($tool->additionaltitle)
		<x-property name="Subtitle">
			{{ $tool->additionaltitle }}
		</x-property>
	@endif

	@if($tool->doi)
		<x-property name="DOI">
			@if($tool->radarstatus==3)
				<a href="https://doi.org/{{ $tool->doi }}">{{ $tool->doi }}</a>
			@else
				{{ $tool->doi }}
			@endif
			<img id="copyDOI" src="{{ asset('images/copy-to-clipboard.png') }}" alt="Copy to Clipboard" style="height: 1.5em; display: inline-block;"><input type="text" id="textDOI" value="{{ $tool->doi }}" class="hidden">
		</x-property>
	@endif
</p>

<p>
	@if($tool->filename)
		<x-button method="GET" class="inline" action="{{ asset($tool->url()) }}">Download</x-button>
	@endif

	@can('update', $tool)
		<x-button method="GET" class="inline" action="{{ route('tools.upload', [$tool->id]) }}">Upload</x-button>
	@endcan
	@can('own', $tool)
		<x-button method="GET" action="{{ route('tools.doi', [$tool]) }}" class="inline">Manage DOI</x-button>
	@endcan
	@can('delete', $tool)
		<x-button method="DELETE" action="{{ route('tools.destroy', [$tool->id]) }}" class="inline">Delete Tool</x-button>
	@endcan
</p>

<p>
	<small><b>Ecosystem ID:</b> {{ $tool->id }}</small>
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
</script>
