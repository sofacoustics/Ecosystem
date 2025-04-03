<x-app-layout>
	@can('update', [App\Models\Dataset::class, $dataset])
		<x-slot name="header">
			<x-database.header :dataset="$dataset" />
		</x-slot>
		<h3>Edit a dataset:</h3>
			
		<div>
			<livewire:dataset-form :dataset="$dataset" />
		</div>		
	@else
		<p>You are not allowed to create dataset because you do not own this database or it is locked for persistent publications.</p>
	@endcan
</x-app-layout>
