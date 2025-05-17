<x-app-layout>
	<x-slot name="header">
		<x-tool.header :tool="$rightsholderable" />
	</x-slot>
	
	<h3>Rightsholders</h3>
	<p>The person(s) or institution(s) owning or managing the property rights of this tool:</p>
	<x-rightsholder.list :rightsholderable=$rightsholderable />

	<livewire:rightsholder-form :rightsholderable="$rightsholderable" />

</x-app-layout>
