<x-app-layout>
	<x-slot name="header">
		<x-database.header :database=$database />
	</x-slot>
	<h3>Keywords</h3>

		<p>Keyword(s) describing the subject focus of the database:</p>
		
	<ul class="list-disc list-inside">
		@forelse($database->keywords as $keyword)
					<li>
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
						<b> {{ $keyword->keywordName }} </b> (
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
			<li>No keywords defined yet.</li>
		@endforelse
	</ul>

	<livewire:keyword-form :database=$database />

</x-app-layout>
