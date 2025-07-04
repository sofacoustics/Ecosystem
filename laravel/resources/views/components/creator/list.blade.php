		
			<b>{{ $creator->creatorName }}</b>
			@if($creator->nameIdentifierSchemeIndex == 1)
				@if($creator->nameIdentifier)
				<a href="{{ $creator->schemeURI($creator->nameIdentifierSchemeIndex).$creator->nameIdentifier }}">
					<img id="visible" src="{{ asset('images/orcid_16x16.webp') }}"
						alt="ORCID: {{ $creator->nameIdentifier }}" 
						title="{{ $creator->nameIdentifier }}" 
						style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 1em; min-width: 1em;"></a>
				@endif
			@elseif ($creator->nameIdentifierSchemeIndex == 2)
				@if($creator->nameIdentifier)
				<a href="{{ $creator->schemeURI($creator->nameIdentifierSchemeIndex).$creator->nameIdentifier }}">
					<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
						alt="ROR: {{ $creator->nameIdentifier }}" 
						alt="{{ $creator->nameIdentifier }}" 
						style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;"></a>
				@endif
			@elseif ($creator->nameIdentifierSchemeIndex == 0)
				<b>{{ $creator->nameIdentifierScheme($creator->nameIdentifierSchemeIndex) }}</b>: {{ $creator->nameIdentifier }}
			@endif
			;
			@if ($creator->creatorAffiliation != null) {{ $creator->creatorAffiliation }} @endif
			@if ($creator->affiliationIdentifier != null) 
				<a href="{{ $creator->schemeURI($creator->affiliationIdentifierSchemeIndex).$creator->affiliationIdentifier }}">
					<img id="visible" src="{{ asset('images/ROR_logo.svg') }}" 
						alt="ROR: {{ $creator->affiliationIdentifier }}" 
						alt="{{ $creator->affiliationIdentifier }}" 
						style="display: inline; margin: 0 auto; width: 100%; height: auto; max-width: 2em; min-width: 2em;">
				</a>
			@endif 
		</li>
