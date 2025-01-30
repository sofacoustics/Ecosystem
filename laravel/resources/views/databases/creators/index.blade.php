<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Creators:</h3>

    <ul class="list-disc list-inside">
        @forelse($database->creators as $creator)
					<li><b>Name</b>: {{ $creator->creatorName }}
						@if ($creator->givenName != null) <b>Given Name</b>: {{ $creator->givenName }}@endif
						@if($creator->familyName != null) <b>Family Name</b>: {{ $creator->familyName }}@endif
						@if ($creator->nameIdentifier != null) <b>{{ $creator->nameIdentifierScheme($creator->nameIdentifierSchemeIndex) }}</b>: {{ $creator->nameIdentifier }}@endif
						@if ($creator->creatorAffiliation != null) <b>Affiliation</b>: {{ $creator->creatorAffiliation }}@endif
						@if ($creator->affiliationIdentifier != null) <b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }}@endif

						@auth
							@if( Auth::user()->id  == $database->user_id)
								<form class="bg-green-100 inline" action="{{ route('creators.edit', [$creator]) }}">
									<button type="submit" class="btn btn-danger btn-sm">Edit</button>
								</form>
								<form class="bg-red-100 inline" method="POST" action="{{ route('creators.destroy', [$creator]) }}">
									@csrf @method('DELETE')
									<button type="submit" class="btn btn-danger btn-sm">Delete</button>
								</form>
							@endif
						@endauth
					</li>
        @empty
					<li>No creators defined.</li>
				@endforelse
    </ul>

		<h3>Add a new creator:</h3>
				<livewire:creator-form :database=$database />


</x-app-layout>
