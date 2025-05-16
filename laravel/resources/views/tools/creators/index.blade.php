<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$commentable" />
	</x-slot>
	<h3>Creators</h3>

		<p>Persons or institutions responsible for creating the tool:</p>

	<ul class="list-disc list-inside">
			@forelse($tool->creators as $creator)
				<li>
					@can('update', $tool)
						<x-button method="GET" action="{{ route('creators.edit', [$creator]) }}" class="inline">
								Edit
						</x-button>
					@endcan
					@can('delete', $tool)
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

	<livewire:creator-form :commentable="$commentable">

</x-app-layout>
