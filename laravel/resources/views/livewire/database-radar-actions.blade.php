<div>
    {{-- Do your work, then step back. --}}
	<h3>RADAR Actions</h3>
	@role('admin')
		<p>ID: {{ $id }}</p>
		<p>State: {{ $state }}</p>
	@endrole

	@can('own', $database)
		@if(!isset($id) || $id === "")
			<x-button wire:click="createdataset">Create Dataset</x-button>
		@endif
		@if($pending)
			<x-button wire:click="startReview">Start Review</x-button>
		@endif
		@if($review)
			<x-button wire:click="endReview">End Review</x-button>
		@endif
	@endcan
	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif
</div>
