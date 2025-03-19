<x-app-layout>
	<x-slot name="header">
		<x-database.header :dataset="$dataset" />
	</x-slot>
	<h3>Edit a dataset:</h3>
		
	<div>
		<livewire:dataset-form :dataset="$dataset" />
	</div>		
	
</x-app-layout>
