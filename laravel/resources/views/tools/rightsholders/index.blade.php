<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$rightsholderable" />
	</x-slot>
	<h3>Rightsholders</h3>
	<p>The person(s) or institution(s) owning or managing the property rights of this tool:</p>
	
	@if(count($rightsholderable->rightsholders)>0)
		<table class="table-auto px-4">
		@foreach($rightsholderable->rightsholders as $rightsholder)
			<tr>
				@can('update', $rightsholderable)
					<td>
						<x-button method="GET" action="{{ route('rightsholders.edit', [$rightsholder]) }}" class="inline">
							Edit
						</x-button>
					</td>
					<td>
						@if($loop->index > 0)
							<x-button method="GET" action="{{ route('rightsholders.up', $rightsholder) }}" class="inline">
								&uarr;
							</x-button>
						@endif
					</td>
					<td>
						@if($loop->index < count($rightsholderable->rightsholders)-1)
						<x-button method="GET" action="{{ route('rightsholders.down', [$rightsholder]) }}" class="inline">
							&darr;
						</x-button>
						@endif
					</td>
				@endcan
				@can('delete', $rightsholderable)
					<td>
						<x-button method="DELETE" action="{{ route('rightsholders.destroy', [$rightsholder]) }}" class="inline">
							Delete
						</x-button>
					</td>
				@endcan
				<td class="px-4">
					<x-rightsholder.list :rightsholder=$rightsholder />
				</td>
			</tr>
		@endforeach
		</table>
	@else
			<li>No rightsholders defined yet.</li>
	@endif

	@can('update', $rightsholderable)
		<livewire:rightsholder-form :rightsholderable="$rightsholderable" />
	@endcan

</x-app-layout>
