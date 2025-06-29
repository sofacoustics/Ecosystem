<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$rightsholderable" />
	</x-slot>
	
	<h3>Rightsholders</h3>
	<p>The person(s) or institution(s) owning or managing the property rights of this database:</p>
	<x-rightsholder.list :rightsholderable=$rightsholderable />

	@can('update', $rightsholderable)
		<livewire:rightsholder-form :rightsholderable="$rightsholderable" />
	@endcan

</x-app-layout>
