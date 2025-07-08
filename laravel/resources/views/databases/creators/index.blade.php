<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$creatorable" />
	</x-slot>
	<h3>Creators</h3>
	<p>Persons or institutions responsible for the content of the research data:</p>
	
	@if(count($creatorable->creators)>0)
		<table class="table-auto px-4">
		@foreach($creatorable->creators as $creator)
			<tr>
				@can('update', $creatorable)
					<td>
						<x-button method="GET" action="{{ route('creators.edit', [$creator]) }}" class="inline">
							Edit
						</x-button>
					</td>
					<td>
						@if($loop->index > 0)
							<x-button method="GET" action="{{ route('creators.up', $creator) }}" class="inline">
								&uarr;
							</x-button>
						@endif
					</td>
					<td>
						@if($loop->index < count($creatorable->creators)-1)
						<x-button method="GET" action="{{ route('creators.down', [$creator]) }}" class="inline">
							&darr;
						</x-button>
						@endif
					</td>
				@endcan
				@can('delete', $creatorable)
					<td>
						<x-button method="DELETE" action="{{ route('creators.destroy', [$creator]) }}" class="inline">
							Delete
						</x-button>
					</td>
				@endcan
				<td class="px-4">
					<x-creator.list :creator=$creator />
				</td>
			</tr>
		@endforeach
		</table>
	@else
			<li>No creators defined yet.</li>
	@endif

	@can('update', $creatorable)
		<livewire:creator-form :creatorable="$creatorable" />
	@endcan

</x-app-layout>
