
			@if(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 1)
			{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				the Database 
				<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}">
					{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
			@elseif(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 2)
				{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				the Tool 
				<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}">
					{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
			@else
					{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\Metadataschema::display($relatedidentifier->relationtype)) }}</b>
					<a href="{{ \App\Models\RelatedIdentifier::externalUrl($relatedidentifier->relatedidentifiertype, $relatedidentifier->name) }}">
					 {{ $relatedidentifier->name }}</a>
					 ({{ \App\Models\Metadataschema::display($relatedidentifier->relatedidentifiertype) }}).
			@endif
		</li>
