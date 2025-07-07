<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$creatorable" />
	</x-slot>
	<h3>Creators</h3>
	<p>Persons or institutions responsible for the content of the research data:</p>
	
	<ul class="list-disc list-inside">
		@forelse($creatorable->creators as $creator)
		<li>
			@can('update', $creatorable)
				<x-button method="GET" action="{{ route('creators.edit', [$creator]) }}" class="inline">
						Edit
				</x-button>
			@endcan
			@can('delete', $creatorable)
				<x-button method="DELETE" action="{{ route('creators.destroy', [$creator]) }}" class="inline">
						Delete
				</x-button>
			@endcan

			<x-creator.list :creator=$creator />
		
		@empty
			<li>No creators defined yet.</li>
		@endforelse
	</ul>

	@can('update', $creatorable)
		<livewire:creator-form :creatorable="$creatorable" />
	@endcan

</x-app-layout>
