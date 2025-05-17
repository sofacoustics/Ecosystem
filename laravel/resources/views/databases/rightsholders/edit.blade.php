<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$rightsholder->rightsholderable" />
	</x-slot>
	<div>
		<livewire:rightsholder-form :rightsholderable="$rightsholder->rightsholderable" :rightsholder=$rightsholder />
	</div>
</x-app-layout>
