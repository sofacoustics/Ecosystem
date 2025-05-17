<x-app-layout>
	<x-slot name="header">
		<x-database.header :database="$publisherable" />
	</x-slot>
	<div>
		<livewire:publisher-form :publisherable="$publisher->publisherable" :publisher=$publisher />
	</div>
</x-app-layout>
