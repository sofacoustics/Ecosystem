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
	<a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }}</a> ({{ $tool->productionyear }})
</h2>

<p>
	@if($tool->additionaltitle)
		<x-property name="Subtitle">
			{{ $tool->additionaltitle }}
		</x-property>
	@endif

	@if($tool->doi)
		<x-property name="DOI">
			<a href="{{ $tool->doi }}">{{ $tool->doi }}</a>
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
	@can('update', $tool)
		<x-button method="GET" action="{{ route('tools.edit', [$tool]) }}" class="inline">Edit</x-button>
	@endcan
	@can('delete', $tool)
		<x-button method="DELETE" action="{{ route('tools.destroy', [$tool->id]) }}" class="inline">Delete Tool</x-button>
	@endcan
</p>

<p>
	<small><b>Ecosystem ID:</b> {{ $tool->id }}</small>
</p>
