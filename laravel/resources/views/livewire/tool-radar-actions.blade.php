<div>
	<h3>RADAR Actions</h3>
		@if($id == null)
			<x-livewire-button wire:click="createDataset" loading="Creating...">Create RADAR Dataset</x-livewire-button>
		@endif

		<x-livewire-button wire:click="startReview" loading="Starting...">Start Review</x-livewire-button>
		<x-livewire-button wire:click="endReview" loading="Ending...">End Review</x-livewire-button>

		@if($canUpload)
			<x-livewire-button wire:click="uploadToRadar" loading="Uploading...">Upload to RADAR</x-livewire-button>
		@endif

		<x-livewire-button wire:click="approvePersistentPublication" 
			wire:confirm="This will set the status to 'Persisently published'. Nothing will happen at the Datathek!">
			Approve Persistent Publication
		</x-livewire-button>
		<x-livewire-button wire:click="rejectPersistentPublication"
			wire:confirm="This will end the review at the Datathek and set the status to 'DOI Assigned'">
			Reject Persistent Publication
		</x-livewire-button>
		<x-livewire-button style='delete' wire:click="resetDOI"
			wire:confirm="This will remove the DOI from the Ecosystem and all links to the Datathek. Nothing will happen at the Datathek!">
			Reset DOI
		</x-livewire-button>
		<x-livewire-button style='delete' wire:click="deleteFromRadar" loading="Deleting..."
			wire:confirm="This will delete the Tool from the Datathek and remove the DOI in the Ecosystem!">
			Delete from RADAR
		</x-livewire-button>

		@if($error)
			<x-alert>{{ $error }}</x-alert>
		@endif
	
</div>
