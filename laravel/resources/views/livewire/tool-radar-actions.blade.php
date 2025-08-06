<div>

	<x-property name="ID">{{ $id }} (File ID: {{ $file_id }})</x-property>
	<x-property name="State">{{ $state }}</x-property>
	<x-property name="DOI">{{ $doi }}</x-property>
	<x-property name="Size">{{ $size }}</x-property>
	<x-property name="Last retrieved">{{ $last_retrieved }}</x-property>
	<x-property name="Internal Status (tooltip available)" 
		title="
0: No RADAR information
1: DOI assigned
2: Publishing and copying to RADAR triggered
3: Copying done and waiting for approval
4: Approved and persistently published"
	>{{ $radar_status }}</x-property>

	<div class="expandable-box" wire:click="toggleExpand">
		@if ($isExpanded)
			<div class="box-content expanded">
				<b>RADAR Response:</b> <pre><code>{{ $radar_content }}</code></pre>
			</div>
		@else
			<div class="box-content collapsed-preview">
				<b>RADAR Response:</b> Click to see the response...
			</div>
		@endif
	</div>
	

	@if("$error" != '')
		<x-alert title='Error!'>{{ $error }}</x-alert>
	@endif

	@if($radar_status == 3)
		<x-livewire-button wire:click="approvePersistentPublication"
			wire:confirm="This will persistently publish the RADAR dataset! This can not be undone!">
			Approve Persistent Publication
		</x-livewire-button>
		<x-livewire-button wire:click="rejectPersistentPublication"
			wire:confirm="This will end the review at the Datathek and set the status to 'DOI Assigned'">
			Reject Persistent Publication
		</x-livewire-button>
	@endif

	<hr>
	<p>For testing purposes</p>
	@if($id == null)
		<x-livewire-button wire:click="createDataset" loading="Creating...">Create RADAR Dataset</x-livewire-button>
	@endif

	<x-livewire-button wire:click="refreshStatus" loading="Refreshing...">Refresh Status</x-livewire-button>
	<x-livewire-button wire:click="validateMetadata" loading="Validating...">Validate Metadata at RADAR</x-livewire-button>
	<x-livewire-button wire:click="startReview" loading="Starting...">Start Review</x-livewire-button>
	<x-livewire-button wire:click="endReview" loading="Ending...">End Review</x-livewire-button>

	@if($canUpload)
		<x-livewire-button wire:click="uploadToRadar" loading="Uploading...">Upload to RADAR</x-livewire-button>
	@endif

	<x-livewire-button style='delete' wire:click="resetDOI"
		wire:confirm="This will remove the DOI from the Ecosystem and all links to the Datathek. Nothing will happen at the Datathek!">
		Reset DOI
	</x-livewire-button>
	<x-livewire-button style='delete' wire:click="deleteFromRadar" loading="Deleting..."
		wire:confirm="This will delete the RADAR dataset from the Datathek and remove the DOI in the Ecosystem!">
		Delete from RADAR
	</x-livewire-button>

	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif

</div>
