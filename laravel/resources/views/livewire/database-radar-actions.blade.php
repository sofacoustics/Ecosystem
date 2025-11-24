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
		wire:confirm="This will delete the RADAR Dataset from the Datathek and remove the DOI in the Ecosystem!">
		Delete from RADAR
	</x-livewire-button>
	<x-livewire-button wire:click="publishToRadar" loading="Publishing to RADAR via job">
		Publish to RADAR via job
	</x-livewire-button>
	@if($error)
		<x-alert>{{ $error }}</x-alert>
	@endif

	<br><hr><br>
	<h2>Services</h2>
	@if(count($jobs) < 1)
		<x-property name="Jobs">No jobs scheduled</x-property>
	@else
		<x-property name="Jobs">{{count($jobs)}} jobs scheduled:</x-property>
		<table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<th>ID</th>
				<th>Datafile</th>
				<th>Attempts</th>
				<th>Created At</th>
			</thead>
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($jobs as $job)
					<tr>
						<td>{{ $job->id }}</td>
						<td><a href="{{ route('datafiles.show', $job->datafile->id) }}" target="_blank">{{ $job->datafile->name }}</a></td>
						<td>{{ $job->attempts }}</td>
						<td>{{ $job->created_at }}</td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
	
	<hr>
	@if(count($logs_failed) < 1)
		<x-property name="Logs">All services have been resolved (even if they failed originally)</x-property>
	@else
		<x-property name="Logs">{{count($logs_failed)}} services have not been resolved:</x-property>
		<table class="table-auto border border-slate-399">
			<thead class="bg-gray-50">
				<th>Datafile ID</th>
				<th>Exit Code</th>
				<th>Created At</th>
				<th></th>
				<th></th>
			</thead>	
			<tbody class="bg-white divide-y divide-gray-200">
				@foreach($logs_failed as $log)
					<tr>
						<td>{{ $log->datafile_id }}</td>
						<td>{{ $log->exit_code }}</td>
						<td>{{ $log->created_at }}</td>
						<td><a href="{{ route('datafiles.show', $log->datafile->id) }}" target="_blank">{{ $log->datafile->name }}</a></td>
						<td><x-button method="POST" class="inline" action="{{ route('datafiles.touch', [$log->datafile]) }}">Rerun service</x-button></td>
					</tr>
				@endforeach
			</tbody>
		</table>
	@endif
</div>
