<ul class="list-disc list-inside">
		@forelse($publisherable->publishers as $publisher)
			<li>
				@can('update', $publisherable)
					<x-button method="GET" action="{{ route('publishers.edit', [$publisher]) }}" class="inline">
						Edit
					</x-button>
				@endcan
				@can('delete', $publisherable)
					<x-button method="DELETE" action="{{ route('publishers.destroy', [$publisher]) }}" class="inline">
						Delete
					</x-button>
				@endcan
				<b>Name</b>: {{ $publisher->publisherName }}
				@if ($publisher->nameIdentifier != null) <b>{{ \App\Models\Creator::nameIdentifierScheme($publisher->nameIdentifierSchemeIndex) }}</b>: 
					@if ($publisher->schemeURI != null) <a href="{{ $publisher->schemeURI }}"> @endif
					{{ $publisher->nameIdentifier }}
					@if ($publisher->schemeURI != null) </a> @endif
				@endif
			</li>
		@empty
			<li>No publishers defined yet.</li>
		@endforelse
</ul>
