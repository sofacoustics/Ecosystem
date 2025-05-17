{{-- the Tool header to put in the app layout slot 'header' --}}

<h2 class="font-semibold text-xl text-gray-800 leading-tight">Tool: 
	<a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }} ({{ $tool->productionyear }})</a>
	@role('admin')
			<small>(ID: {{ $tool->id }})</small>
	@endrole
</h2>

<x-property name="Additional Title">
  {{ $tool->additionaltitle }}
</x-property>

<x-property name="Manager at the Ecosystem">
  {{ \App\Models\User::find($tool->user_id)->name }}
</x-property>

	<x-button method="GET" class="inline" action="{{ asset($tool->url()) }}">Download</x-button>

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
