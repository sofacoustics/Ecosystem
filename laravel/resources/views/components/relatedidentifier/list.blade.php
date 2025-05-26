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
			<b>{{ \App\Models\Database::relationDisplay($relatedidentifier->relationtype) }}:</b> 
			{{ $relatedidentifier->name }} 
			({{ \App\Models\Database::relatedidentifierDisplay($relatedidentifier->relatedidentifiertype) }}).
		</li>
	@empty
		<li>No relations defined yet.</li>
	@endforelse
</ul>