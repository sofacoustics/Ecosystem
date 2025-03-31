<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database />
	</x-slot>
	<h3>Rightsholders</h3>

		<p>The person(s) or institution(s) owning or managing the property rights of this database:</p>

	<ul class="list-disc list-inside">
			@forelse($database->rightsholders as $rightsholder)
				<li>
					@can('update', $database)
							<x-button method="GET" action="{{ route('rightsholders.edit', [$rightsholder]) }}" class="inline">
									Edit
							</x-button>
					@endcan
					@can('delete', $database)
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

	<livewire:rightsholder-form :database=$database />

</x-app-layout>
