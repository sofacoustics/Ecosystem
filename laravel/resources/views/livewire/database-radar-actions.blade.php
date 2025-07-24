<div>

	<li><b>ID:</b> {{ $id }}</li>
	<li><b>State:</b> {{ $state }}</li>
	<li><b>Internal Status:</b> {{ $radar_status }}</li>
	<li><b>DOI:</b> {{ $doi }}</li>
	<li><b>Size:</b> {{ $size }}</li>
	<li><b>Last retrieved:</b> {{ $last_retrieved }}</li>

	<div class="expandable-box" wire:click="toggleExpand">
		@if ($isExpanded)
			<div class="box-content expanded">
				<li><b>RADAR Response:</b> <pre><code>{{ $radar_content }}</code></pre></li>
			</div>
		@else
			<div class="box-content collapsed-preview">
				<li><b>RADAR Response:</b> Click to see the response...</li>
			</div>
		@endif
	</div>
	

	@if("$error" != '')
		<x-alert title='Error!'>{{ $error }}</x-alert>
	@endif
		
	@if($id == null)
		<x-livewire-button wire:click="createDataset" loading="Creating...">Create RADAR Dataset</x-livewire-button>
	@endif

	<x-livewire-button wire:click="refreshStatus" loading="Refreshing...">Refresh Status</x-livewire-button>
	<x-livewire-button wire:click="startReview" loading="Starting...">Start Review</x-livewire-button>
	<x-livewire-button wire:click="endReview" loading="Ending...">End Review</x-livewire-button>

	@if($canUpload)
		<x-livewire-button wire:click="uploadToRadar" loading="Uploading...">Upload to RADAR</x-livewire-button>
	@endif

	@if($radar_status == 2)
		<x-livewire-button wire:click="approvePersistentPublication"
			wire:confirm="This will persistently publish the RADAR dataset! This can not be undone!">
			Approve Persistent Publication
		</x-livewire-button>
		<x-livewire-button wire:click="rejectPersistentPublication"
			wire:confirm="This will end the review at the Datathek and set the status to 'DOI Assigned'">
			Reject Persistent Publication
		</x-livewire-button>
	@endif
	<x-livewire-button style='delete' wire:click="resetDOI"
		wire:confirm="This will remove the DOI from the Ecosystem and all links to the Datathek. Nothing will happen at the Datathek!">
		Reset DOI
	</x-livewire-button>
	<x-livewire-button style='delete' wire:click="deleteFromRadar" loading="Deleting..."
		wire:confirm="This will delete the Database from the Datathek and remove the DOI in the Ecosystem!">
		Delete from RADAR
	</x-livewire-button>
	<x-livewire-button wire:click="publishToRadar" loading="Publishing to RADAR via job">
		Publish to RADAR via job
	</x-livewire-button>
	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif

</div>
