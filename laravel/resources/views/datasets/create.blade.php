	<x-app-layout>
		<x-slot name="header">
				<x-database.header :database=$database />
		</x-slot>
		@can('update', $database)
			<h3>Create a new dataset:</h3>
			<div>
				<livewire:dataset-form :database=$database />
			</div>
		@else
			<p>You are not allowed to create dataset because you do not own this database or it is locked for persistent publications.</p>
		@endcan
	</x-app-layout>
