<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$publisherable" />
	</x-slot>
	
	<h3>Publishers</h3>
	<p>Person(s) or institution(s) responsible for publishing this database at the Ecosystem:</p>
	<ul class="list-disc list-inside">
		@forelse($publisherable->publishers as $publisher)
		<li>
			@can('update', $publisherable)
				<x-button method="GET" action="{{ route('publishers.edit', [$publisher]) }}" class="inline">
					Edit
				</x-button>
			@endcan
			@can('delete', $publisherable)
				<x-button method="DELETE" action="{{ route('publishers.destroy', [$publisher]) }}" class="inline">
					Delete
				</x-button>
			@endcan
			<x-publisher.list :publisher=$publisher />
		@empty
			<li>No publishers defined yet.</li>
		@endforelse
	</ul>

	@can('update', $publisherable)
		<livewire:publisher-form :publisherable="$publisherable" />
	@endcan
</x-app-layout>
