
			@if(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 1)
			{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				@if(\App\Models\RelatedIdentifier::internalName($relatedidentifier->name) == null)
					<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}" target="_blank">a deleted Database</a>.
				@else
					the Database 
					<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}" target="_blank">
						{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
				@endif
				
			@elseif(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 2)
				{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				@if(\App\Models\RelatedIdentifier::internalName($relatedidentifier->name) == null)
					<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}" target="_blank">a deleted Tool</a>.
				@else
					the Tool 
					<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}" target="_blank">
						{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
				@endif
				
			@else
					{{$relatedidentifier->relatedidentifierable->title}} <b>{{ strtolower(\App\Models\Metadataschema::display($relatedidentifier->relationtype)) }}</b>
					<a href="{{ \App\Models\RelatedIdentifier::externalUrl($relatedidentifier->relatedidentifiertype, $relatedidentifier->name) }}" target="_blank">
					 {{ $relatedidentifier->name }}</a>
					 ({{ \App\Models\Metadataschema::display($relatedidentifier->relatedidentifiertype) }}).
			@endif
		</li>
