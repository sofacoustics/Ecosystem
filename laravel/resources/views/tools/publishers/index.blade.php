<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$publisherable" />
	</x-slot>
	<h3>Publishers</h3>
	<p>Person(s) or institution(s) responsible for publishing this database at the Ecosystem. </p>
	<p>Per default, the database is published here, at the SONICOM Ecosystem, thus we start with this entry. However, this entry can be removed, edited, and extended.</p>
	<p>Note that the publisher is used to formulate the citation, thus consider a proper entry.</p>

	@if(count($publisherable->publishers)>0)
		<table class="table-auto px-4">
		@foreach($publisherable->publishers as $publisher)
			<tr>
				@can('update', $publisherable)
					<td>
						<x-button method="GET" action="{{ route('publishers.edit', [$publisher]) }}" class="inline">
							Edit
						</x-button>
					</td>
					<td>
						@if($loop->index > 0)
							<x-button method="GET" action="{{ route('publishers.up', $publisher) }}" class="inline">
								&uarr;
							</x-button>
						@endif
					</td>
					<td>
						@if($loop->index < count($publisherable->publishers)-1)
						<x-button method="GET" action="{{ route('publishers.down', [$publisher]) }}" class="inline">
							&darr;
						</x-button>
						@endif
					</td>
				@endcan
				@can('delete', $publisherable)
					<td>
						<x-button method="DELETE" action="{{ route('publishers.destroy', [$publisher]) }}" class="inline">
							Delete
						</x-button>
					</td>
				@endcan
				<td class="px-4">
					<x-publisher.list :publisher=$publisher />
				</td>
			</tr>
		@endforeach
		</table>
	@else
			<li>No publishers defined yet.</li>
	@endif

	@can('update', $publisherable)
		<livewire:publisher-form :publisherable="$publisherable" />
	@endcan

</x-app-layout>
