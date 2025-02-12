<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Rightsholders:</h3>

    <ul class="list-disc list-inside">
        @forelse($database->rightsholders as $rightsholder)
            <li><b>Name</b>: {{ $rightsholder->rightsholderName }}
								@if ($rightsholder->nameIdentifier != null) <b>{{ \App\Models\Creator::nameIdentifierScheme($rightsholder->nameIdentifierSchemeIndex) }}</b>: 
									@if ($rightsholder->schemeURI != null) <a href="{{ $rightsholder->schemeURI }}"> @endif
									{{ $rightsholder->nameIdentifier }}
									@if ($rightsholder->schemeURI != null) </a> @endif
								@endif
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
            </li>
        @empty
            <li>No rights holders defined.</li>
        @endforelse
    </ul>

    <livewire:rightsholder-form :database=$database />

</x-app-layout>
