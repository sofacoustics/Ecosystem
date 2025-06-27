<ul class="list-disc list-inside">
	@forelse($relatedidentifierable->relatedidentifiers as $relatedidentifier)
		<li>
			@can('update', $relatedidentifierable)
				<x-button method="GET" action="{{ route('relatedidentifiers.edit', [$relatedidentifier]) }}" class="inline">
					Edit
				</x-button>
			@endcan
			@can('delete', $relatedidentifierable)
				<x-button method="DELETE" action="{{ route('relatedidentifiers.destroy', [$relatedidentifier]) }}" class="inline">
					Delete
				</x-button>
			@endcan
			@if(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 1)
				<b>... {{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				the Database 
				<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}">
					{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
			@elseif(\App\Models\RelatedIdentifier::isInternalLink($relatedidentifier->name) == 2)
				<b>... {{ strtolower(\App\Models\RelatedIdentifier::displayRelation($relatedidentifier->relationtype)) }}</b>
				the Tool 
				<a href="{{ \App\Models\RelatedIdentifier::internalUrl($relatedidentifier->name) }}">
					{{ \App\Models\RelatedIdentifier::internalName($relatedidentifier->name) }}</a>.
			@else
					<b>... {{ strtolower(\App\Models\Metadataschema::display($relatedidentifier->relationtype)) }}</b>
					<a href="{{ \App\Models\RelatedIdentifier::externalUrl($relatedidentifier->relatedidentifiertype, $relatedidentifier->name) }}">
					 {{ $relatedidentifier->name }}</a>
					 ({{ \App\Models\Metadataschema::display($relatedidentifier->relatedidentifiertype) }}).
			@endif
		</li>
	@empty
		<li>No relations defined yet.</li>
	@endforelse
</ul>