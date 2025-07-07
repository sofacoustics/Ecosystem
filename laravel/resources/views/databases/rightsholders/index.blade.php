<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$rightsholderable" />
	</x-slot>
	
	<h3>Rightsholders</h3>
	<p>The person(s) or institution(s) owning or managing the property rights of this database:</p>
	<ul class="list-disc list-inside">
		@forelse($rightsholderable->rightsholders as $rightsholder)
			<li>
				@can('update', $rightsholderable)
					<x-button method="GET" action="{{ route('rightsholders.edit', [$rightsholder]) }}" class="inline">
							Edit
					</x-button>
				@endcan
				@can('delete', $rightsholderable)
					<x-button method="DELETE" action="{{ route('rightsholders.destroy', [$rightsholder]) }}" class="inline">
							Delete
					</x-button>
				@endcan
				<x-rightsholder.list :rightsholder=$rightsholder />
			</li>
		@empty
			<li>No rights holders defined yet.</li>
		@endforelse
	</ul>

	@can('update', $rightsholderable)
		<livewire:rightsholder-form :rightsholderable="$rightsholderable" />
	@endcan

</x-app-layout>
