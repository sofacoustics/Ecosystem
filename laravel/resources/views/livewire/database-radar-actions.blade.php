<div>
	<x-property name="ID">{{ $id }}</x-property>
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
	@if($radar_status == 2 && $totalDatasets !== null)
		{{-- refresh the copy progress every 30 s while publishing (database queries only) --}}
		<div wire:poll.30s="updateCopyProgress">
			<x-property name="Copied datasets">{{ $copiedDatasets }} of {{ $totalDatasets }}</x-property>
		</div>
	@endif

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

	{{-- status 2, or jobs still open (a batch may still run after the status has been reset, e.g. after an upload error) --}}
	@if($radar_status == 2 || $publishing)
		<x-livewire-button wire:click="stopPublishing" loading="Stopping..."
			title="Cancel the upload to RADAR and reset the status to 'DOI assigned'. Files already uploaded are kept, so publishing again continues where it stopped."
			wire:confirm="This will stop publishing this database: the upload batch will be cancelled and the status set back to 'DOI assigned'. Files already uploaded to RADAR are kept (use 'Empty RADAR dataset' afterwards, when no upload is running any more). Continue?">
			Stop publishing
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

	<x-livewire-button wire:click="uploadToRadarInBatches" loading="Uploading...">Upload to RADAR in batches</x-livewire-button>

	@if($emptying)
		<x-livewire-button :disabled="true" title="A background job is deleting the RADAR folders of this database. Use 'Refresh Status' to check.">Emptying RADAR dataset in progress...</x-livewire-button>
	@else
		<x-livewire-button wire:click="emptyDataset" :disabled="empty($size)" loading="Starting..."
			title="Empty the RADAR dataset in the background (remove the data, but keep the metadata and DOI)"
			wire:confirm="This will delete all uploaded files of this database from the RADAR dataset (DOI and metadata are kept). It runs in the background and takes about 1 s per dataset. Continue?">
			Empty RADAR dataset
		</x-livewire-button>
	@endif

	<x-livewire-button style='delete' wire:click="resetDOI"
		wire:confirm="This will remove the DOI from the Ecosystem and all links to the Datathek. Nothing will happen at the Datathek!">
		Reset DOI
	</x-livewire-button>
	<x-livewire-button style='delete' wire:click="deleteFromRadar" loading="Deleting..."
		wire:confirm="This will delete the RADAR Dataset from the Datathek and remove the DOI in the Ecosystem!">
		Delete from RADAR
	</x-livewire-button>
	<x-livewire-button wire:click="publishToRadar" loading="Publishing to RADAR via job">
		Publish to RADAR via job
	</x-livewire-button>
	<x-livewire-button wire:click="publishInBatchToRadar" loading="Publishing to RADAR via batch job">
		Publish to RADAR via batch job
	</x-livewire-button>

</div>
