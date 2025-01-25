<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Creators</h3>
    <p>The following {{ count($database->creators) }} creators are assigned with the database "{{ $database->title }}"</p>

    <ul class="list-disc list-inside">
        @foreach($database->creators as $creator)
					<p><b>Name</b>: {{ $creator->creatorName }}
						@if ($creator->givenName != null) <b>Given Name</b>: {{ $creator->givenName }}@endif 
						@if($creator->givenName != null) <b>Family Name</b>: {{ $creator->familyName }}@endif
						@if ($creator->nameIdentifier != null) <b>{{ $creator->nameIdentifierScheme }}</b>: {{ $creator->nameIdentifier }}@endif
						@if ($creator->creatorAffiliation != null) <b>Affiliation</b>: {{ $creator->creatorAffiliation }}@endif
						@if ($creator->affiliationIdentifier != null) <b>{{ $creator->affiliationIdentifierScheme }}</b>: {{ $creator->affiliationIdentifier }}@endif
					</p> 
        @endforeach
    </ul>
    {{-- include a form to create a new creator --}}
			{{-- @can('create', App\Models\Creator::class)
        @if(count($database->creators) == 0)
            <livewire:creator-form :database=$database />
        @else
            <p>Since there are datasets in this database, the dataset definitions may not be altered.</p>
        @endif
			@endcan --}}
</x-app-layout>
