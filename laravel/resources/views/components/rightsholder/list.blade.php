<b>{{ $rightsholder->rightsholderName }}</b>
@if ($rightsholder->nameIdentifier) 
	@if($rightsholder->nameIdentifierSchemeIndex==1) <!-- ORCID -->
		<a href="{{ $rightsholder->schemeURI($rightsholder->nameIdentifierSchemeIndex).$rightsholder->nameIdentifier }}">
			<img id="visible" src="{{ asset('images/orcid_16x16.webp') }}"
				alt="ORCID: {{ $rightsholder->nameIdentifier }}" 
				title="{{ $rightsholder->nameIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1em; min-width: 1em;"></a>
	@elseif($rightsholder->nameIdentifierSchemeIndex==2) <!-- ROR -->
		<a href="{{ $rightsholder->schemeURI($rightsholder->nameIdentifierSchemeIndex).$rightsholder->nameIdentifier }}">
			<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
				alt="ROR: {{ $rightsholder->nameIdentifier }}" 
				title="{{ $rightsholder->nameIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;"></a>
	@else
		<a href="{{ $rightsholder->schemeURI }}">
			{{ $rightsholder->nameIdentifier }}
		</a>
	@endif
@endif