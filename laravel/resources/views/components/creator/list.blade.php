
<b>{{ $creator->creatorName }}</b>
@if($creator->familyName)<!-- person -->
	@if($creator->nameIdentifier)
		@if($creator->nameIdentifierSchemeIndex==1)<!-- ORCID -->
			<a href="{{ $creator->schemeURI($creator->nameIdentifierSchemeIndex).$creator->nameIdentifier }}">
				<img id="visible" src="{{ asset('images/orcid_16x16.webp') }}"
					alt="ORCID: {{ $creator->nameIdentifier }}" 
					title="{{ $creator->nameIdentifier }}" 
					style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1em; min-width: 1em;"></a>
		@else
			<a href="{{ $creator->schemeURI($creator->nameIdentifierSchemeIndex).$creator->nameIdentifier }}">
				{{ $creator->nameIdentifier }}
			</a>
		@endif
	@endif
	@if($creator->creatorAffiliation)<!-- person with affiliation -->
		, {{$creator->creatorAffiliation}} 
		@if($creator->affiliationIdentifierScheme==2) <!-- ROR -->
			<a href="{{ $creator->schemeURI($creator->affiliationIdentifierScheme).$creator->affiliationIdentifier }}">
			<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
				alt="ROR: {{ $creator->affiliationIdentifier }}" 
				alt="{{ $creator->affiliationIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;"></a>
		@else
			<a href="{{ $creator->schemeURI($creator->affiliationIdentifierScheme).$creator->affiliationIdentifier }}">
				{{ $creator->affiliationIdentifier }}
			</a>
		@endif
	@endif
@else <!-- institution -->
	@if($creator->nameIdentifier)
		@if($creator->nameIdentifierSchemeIndex==2) <!-- ROR -->
		<a href="{{ $creator->schemeURI($creator->nameIdentifierSchemeIndex).$creator->nameIdentifier }}">
			<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
				alt="ROR: {{ $creator->nameIdentifier }}" 
				alt="{{ $creator->nameIdentifier }}" 
				style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;"></a>
		@else
			<a href="{{ $creator->schemeURI($creator->affiliationIdentifierScheme).$creator->affiliationIdentifier }}">
				{{ $creator->nameIdentifier }}
			</a>
		@endif
	@endif
@endif

