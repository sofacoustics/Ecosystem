
<ul class="list-disc list-inside">
	@forelse($keywordable->keywords as $keyword)
		<li>
			@can('update', $keywordable)
				<x-button method="GET" action="{{ route('keywords.edit', [$keyword]) }}" class="inline">
					Edit
				</x-button>
			@endcan
			@can('delete', $keywordable)
				<x-button method="DELETE" action="{{ route('keywords.destroy', [$keyword]) }}" class="inline">
					Delete
				</x-button>
			@endcan
			<b>{{ $keyword->keywordName }}</b> 
			@if($keyword->classificationCode)
				(@if ($keyword->schemeURI != null)<a href="{{ $keyword->schemeURI }}">@endif{{ \App\Models\Keyword::keywordScheme($keyword->keywordSchemeIndex)}}@if ($keyword->schemeURI != null)</a>@endif: 
				@if ($keyword->valueURI != null)<a href="{{ $keyword->valueURI }}">@endif{{ $keyword->classificationCode }}@if ($keyword->valueURI != null)</a>@endif)
			@endif
		</li>
		</li>
	@empty
		<li>No keywords defined yet.</li>
	@endforelse
</ul>
