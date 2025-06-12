<div>
    {{-- Do your work, then step back. --}}
	<h3>RADAR Actions</h3>
	@role('admin')
		<p>ID: {{ isset($id) ? "$id" : 'There is no RADAR id for this database' }}</p>
		<p>State: {{ $state }}</p>
	@endrole

	@can('own', $database)
		@if($id == null)
			<x-livewire-button wire:click="createDataset" loading="Creating...">Create RADAR Dataset</x-livewire-button>
		@endif
		@if($pending)
			<x-livewire-button wire:click="startReview" loading="Starting...">Start Review</x-livewire-button>
		@endif
		@if($review)
			<x-livewire-button wire:click="endReview" loading="Ending...">End Review</x-livewire-button>
		@endif
		@if($canUpload)
			<x-livewire-button wire:click="uploadToRadar" loading="Uploading...">Upload to RADAR</x-livewire-button>
		@endif

		<x-button wire:click="resetDOI">Reset DOI and the Persistent Publication</x-button>

		@if($radarstatus == 2)
			<x-button wire:click="approvePublication">Approve the Persistent Publication</x-button>
			<x-livewire-button wire:click="resetDOI" loading="Resetting...">Reset DOI and the Persistent Publication</x-livewire-button>
		@endif

		@if(isset($id))
			<x-livewire-button style='delete' wire:click="deleteFromRadar" loading="Deleting...">Delete from RADAR</x-livewire-button>
		@endif
	@endcan
	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif
	

</div>
