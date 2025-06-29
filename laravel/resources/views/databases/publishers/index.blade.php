<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$publisherable" />
	</x-slot>
	
	<h3>Publishers</h3>
	<p>Person(s) or institution(s) responsible for publishing this database at the Ecosystem:</p>
	<x-publisher.list :publisherable=$publisherable />

	@can('update', $publisherable)
		<livewire:publisher-form :publisherable="$publisherable" />
	@endcan
</x-app-layout>
