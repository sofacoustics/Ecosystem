<x-app-layout>
	<x-slot name="header">
			<x-database.header :database=$database />
	</x-slot>
	<h3>Create a new dataset:</h3>
	<div>
		 <!-- <p>resources/views/datasets/create.blade.php</p> --!>
		<livewire:dataset-form :database=$database />
	</div>
</x-app-layout>
