<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$relatedidentifierable" />
	</x-slot>
	<h3>Relations</h3>
	<p>List of relations with the tool:</p>
	
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
				
				<x-relatedidentifier.list :relatedidentifier=$relatedidentifier />
			</li>

		@empty
			<li>No relations defined yet.</li>
		@endforelse
	</ul>
	
	@can('update', $relatedidentifierable)
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifierable" />
	@endcan
	
</x-app-layout>
