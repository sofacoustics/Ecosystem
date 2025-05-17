<ul class="list-disc list-inside">
		@forelse($rightsholderable->rightsholders as $rightsholder)
			<li>
				@can('update', $rightsholderable)
						<x-button method="GET" action="{{ route('rightsholders.edit', [$rightsholder]) }}" class="inline">
								Edit
						</x-button>
				@endcan
				@can('delete', $rightsholderable)
						<x-button method="DELETE" action="{{ route('rightsholders.destroy', [$rightsholder]) }}" class="inline">
								Delete
						</x-button>
				@endcan
				<b>Name</b>: {{ $rightsholder->rightsholderName }}
				@if ($rightsholder->nameIdentifier != null) <b>{{ \App\Models\Creator::nameIdentifierScheme($rightsholder->nameIdentifierSchemeIndex) }}</b>: 
					@if ($rightsholder->schemeURI != null) <a href="{{ $rightsholder->schemeURI }}"> @endif
					{{ $rightsholder->nameIdentifier }}
					@if ($rightsholder->schemeURI != null) </a> @endif
				@endif
			</li>
		@empty
			<li>No rights holders defined yet.</li>
		@endforelse
</ul>
