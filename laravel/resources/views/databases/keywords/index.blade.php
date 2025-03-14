<x-app-layout>
    <x-slot name="header">
        <x-database.header :database=$database />
    </x-slot>
    <h3>Subject Area:</h3>

    <ul class="list-disc list-inside">
        @forelse($database->keywords as $keyword)
					<li><b> {{ $keyword->keywordName }} </b> (
						@if ($keyword->schemeURI != null)<a href="{{ $keyword->schemeURI }}"> @endif
							{{ \App\Models\Keyword::keywordScheme($keyword->keywordSchemeIndex)}}
						@if ($keyword->schemeURI != null)</a> @endif </b>: 
						@if ($keyword->valueURI != null)<a href="{{ $keyword->valueURI }}"> @endif
						{{ $keyword->classificationCode }}
						@if ($keyword->valueURI != null)</a> @endif )
					</li>

							@can('update', $database)
									<x-button method="GET" action="{{ route('keywords.edit', [$keyword]) }}" class="inline">
											Edit
									</x-button>
							@endcan
							@can('delete', $database)
									<x-button method="DELETE" action="{{ route('keywords.destroy', [$keyword]) }}" class="inline">
											Delete
									</x-button>
							@endcan
          </li>
        @empty
            <li>No keywords defined.</li>
        @endforelse
    </ul>

    <livewire:keyword-form :database=$database />

</x-app-layout>
