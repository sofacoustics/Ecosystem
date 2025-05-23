<ul class="list-disc list-inside">
	@forelse($creatorable->creators as $creator)
		<li>
			@can('update', $creatorable)
				<x-button method="GET" action="{{ route('creators.edit', [$creator]) }}" class="inline">
						Edit
				</x-button>
			@endcan
			@can('delete', $creatorable)
				<x-button method="DELETE" action="{{ route('creators.destroy', [$creator]) }}" class="inline">
						Delete
				</x-button>
			@endcan
		
			<b>Name</b>: {{ $creator->creatorName }}
			@if ($creator->givenName != null)
				<b>Given Name</b>: {{ $creator->givenName }}
			@endif
			@if ($creator->familyName != null)
				<b>Family Name</b>: {{ $creator->familyName }}
			@endif
			@if ($creator->nameIdentifier != null)
				<b>{{ $creator->nameIdentifierScheme($creator->nameIdentifierSchemeIndex) }}</b>:
				{{ $creator->nameIdentifier }}
			@endif
			@if ($creator->creatorAffiliation != null)
				<b>Affiliation</b>: {{ $creator->creatorAffiliation }}
			@endif
			@if ($creator->affiliationIdentifier != null)
				<b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }}
			@endif
		</li>
	@empty
		<li>No creators defined yet.</li>
	@endforelse
</ul>