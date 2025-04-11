	<x-app-layout>
		@can('create', [App\Models\Dataset::class, $database])
			<x-slot name="header">
					<x-database.header :database=$database />
			</x-slot>
			<h3>Create a new dataset:</h3>
			<div>
				 <!-- <p>resources/views/datasets/create.blade.php</p> --!>
				<livewire:dataset-form :database=$database />
			</div>
		@else
			<p>You are not allowed to create dataset because you do not own this database or it is locked for persistent publications.</p>
		@endcan
	</x-app-layout>
