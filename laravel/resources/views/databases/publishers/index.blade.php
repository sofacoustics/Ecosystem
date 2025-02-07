<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Publishers:</h3>

    <ul class="list-disc list-inside">
        @forelse($database->publishers as $publisher)
            <li><b>Name</b>: {{ $publisher->publisherName }}
								@if ($publisher->nameIdentifier != null) <b>{{ \App\Models\Creator::nameIdentifierScheme($publisher->nameIdentifierSchemeIndex) }}</b>: 
									@if ($publisher->schemeURI != null) <a href="{{ $publisher->schemeURI }}"> @endif
									{{ $publisher->nameIdentifier }}
									@if ($publisher->schemeURI != null) </a> @endif
								@endif
                @can('update', $database)
                    <x-button method="GET" action="{{ route('publishers.edit', [$publisher]) }}" class="inline">
                        Edit
                    </x-button>
                @endcan
                @can('delete', $database)
                    <x-button method="DELETE" action="{{ route('publishers.destroy', [$publisher]) }}" class="inline">
                        Delete
                    </x-button>
                @endcan
            </li>
        @empty
            <li>No publishers defined.</li>
        @endforelse
    </ul>

    <livewire:publisher-form :database=$database />

</x-app-layout>
