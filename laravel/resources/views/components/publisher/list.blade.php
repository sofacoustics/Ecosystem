<b>{{ $publisher->publisherName }}</b>
@if ($publisher->nameIdentifier) 
	@if($publisher->nameIdentifierSchemeIndex==1) <!-- ORCID -->
		<a href="{{ $publisher->schemeURI($publisher->nameIdentifierSchemeIndex).$publisher->nameIdentifier }}">
			<img id="visible" src="{{ asset('images/orcid_16x16.webp') }}"
				alt="ORCID: {{ $publisher->nameIdentifier }}" 
				title="{{ $publisher->nameIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1em; min-width: 1em;"></a>
	@elseif($publisher->nameIdentifierSchemeIndex==2) <!-- ROR -->
		<a href="{{ $publisher->schemeURI($publisher->nameIdentifierSchemeIndex).$publisher->nameIdentifier }}">
			<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
				alt="ROR: {{ $publisher->nameIdentifier }}" 
				alt="{{ $publisher->nameIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;"></a>
	@else
		<a href="{{ $publisher->schemeURI }}">
			{{ $publisher->nameIdentifier }}
		</a>
	@endif
@endif
