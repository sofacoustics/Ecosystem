<div>
    {{-- Do your work, then step back. --}}
	<h3>RADAR Actions</h3>
	@role('admin')
		<p>ID: {{ isset($id) ? "$id" : 'There is no RADAR id for this database' }}</p>
		<p>State: {{ $state }}</p>
	@endrole

	@can('own', $database)
		@if($id == null)
			<x-button wire:click="createDataset">Create RADAR Dataset</x-button>
		@endif
		@if($pending)
			<x-button wire:click="startReview">Start Review</x-button>
		@endif
		@if($review)
			<x-button wire:click="endReview">End Review</x-button>
		@endif
		@if($canUpload)
			{{--
			<x-livewire-button wire:click="uploadToRadar" loading="Uploading...">Upload to RADAR</x-livewire-button>
			--}}
			<x-button wire:click="uploadToRadar">Upload to RADAR</x-button>
		@endif
		@if(isset($id))
			<x-button method="DELETE" wire:click="deleteFromRadar">Delete from RADAR</x-button>
		@endif
	@endcan
	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif
</div>
