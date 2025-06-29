<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$rightsholder->rightsholderable" />
	</x-slot>
	@can('update', $rightsholderable)
		<livewire:rightsholder-form :rightsholderable="$rightsholder->rightsholderable" :rightsholder=$rightsholder />
	@else
		You can not edit this rightsholder because you do not own the corresponding database. 
	@endcan
</x-app-layout>
