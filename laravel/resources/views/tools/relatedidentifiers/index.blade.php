<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$relatedidentifierable" />
	</x-slot>
	<h3>Relations</h3>
	<p>List of relations with the tool:</p>
	
	@if(count($relatedidentifierable->relatedidentifiers)>0)
		<table class="table-auto px-4">
			@foreach($relatedidentifierable->relatedidentifiers as $relatedidentifier)
				<tr>
					@can('update', $relatedidentifierable)
						<td>
							<x-button method="GET" action="{{ route('relatedidentifiers.edit', [$relatedidentifier]) }}" class="inline">
								Edit
							</x-button>
						</td>
						<td>
							@if($loop->index > 0)
								<x-button method="GET" action="{{ route('relatedidentifiers.up', $relatedidentifier) }}" class="inline">
									&uarr;
								</x-button>
							@else
								&nbsp;
							@endif
						</td>
						<td>
							@if($loop->index < count($relatedidentifierable->relatedidentifiers)-1)
							<x-button method="GET" action="{{ route('relatedidentifiers.down', [$relatedidentifier]) }}" class="inline">
								&darr;
							</x-button>
							@else
								&nbsp;
							@endif
						</td>				
					@endcan
					@can('delete', $relatedidentifierable)
						<td>
							<x-button method="DELETE" action="{{ route('relatedidentifiers.destroy', [$relatedidentifier]) }}" class="inline">
								Delete
							</x-button>
						</td>
					@endcan
					<td class="px-4">
						<x-relatedidentifier.list :relatedidentifier=$relatedidentifier />
					</td>
				</tr>
			@endforeach
		</table>
	@else
		<ul class="list-disc list-outside px-5">
			<li>No relations defined yet.</li>
		</ul>
	@endif
	
	@can('update', $relatedidentifierable)
		<livewire:related-identifier-form :relatedidentifierable="$relatedidentifierable" />
	@endcan
	
</x-app-layout>
