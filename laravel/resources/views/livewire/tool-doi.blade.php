<div>
<h3>DOI Assignment:</h3>
@if($radar_status==null || $radar_status==0)
	<p><b>DOI Status:</b> No DOI assigned.</p>
	@if($tool->metadataValidate())
		<p><b>Metadata Status:</b> Invalid, with following problems:</p>
		<p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded font-bold">{!! nl2br($tool->metadataValidate()) !!}</p>
	@else
		<p><b>Metadata Status:</b> Valid</p>
		<p>By clicking the button below, a DOI can be assigned to your tool. This can be useful when submitting a manuscript and a DOI is required, but the tool is expected to be modified during the review process.</p>
		<p>After clicking the button below, the following will happen:
		<ul class="list-disc list-outside px-5">
			<li>Your tool will be registered at the ÖAW Datathek as a new "Datathek dataset": Only your metadata will be sent to the Datathek and this is a quick process. Your data will be <b>not</b> sent to the Datathek. 
			<li>A DOI for your tool will be requested and assigned to your tool.
			<li>You still will be able to modify your tool and metadata. 
		</ul>
		
		<x-livewire-button
			wire:click="assignDOI" 
			wire:confirm="Are you sure to assign a DOI to your tool?

Note: Once the DOI will be assigned, it will be a permanent part of your tool. This operation is irreversible!"
			loading="Assigning DOI...">
				Assign DOI
		</x-livewire-button>
	@endif
@else
	<b>Status:</b>
	<ul class="list-disc list-outside px-5">
		<li>DOI: Assigned (<b>{{ $doi }}</b>).
		<li>DOI link: https://doi.org/{{$doi}}. When persistently published, the tool will be available under this link.
		@if($tool->metadataValidate())
			<li>Metadata: Invalid, with following problems:<br>
				<p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded font-bold">{!! nl2br($tool->metadataValidate()) !!}</p>
			</li>
		@else
			<li>Metadata: Valid</li>
		@endif
	</ul>
@endif
<p></p>
<hr>

<h3>Persistent Publication:</h3>
@if($radar_status==null || $radar_status==0)
	<p>In order to persistently publish your tool, assign a DOI first.</p>
@else
	@if($radar_status==1)
		@if($tool->metadataValidate())
			<p>In order to persistently publish your tool, provide valid metadata first.</p>
		@else
			@if($tool->filename==null)
				<p>In order to persistently publish your tool, upload your tool first.</p>
			@else
				<p>By clicking the button below, your tool will be submitted for a persistent publication to the Datathek.</p>
				<p>To this end, the following will happen:</p>
				<ul class="list-disc list-outside px-5">
					<li>The metadata at the Datathek will be updated.
					<li>The tool will be locked. <b>No edits will be allowed!</b>
					<li>The process of copying your tool to the Datathek will be started. Depending on the size of your tool, this might take a while.
					<li>The curator of the Datathek will be notified for approval. 
					<li>Once the curator approves your tool, it will be published at the Datathek and the DOI will be valid.
				</ul>
				
				<x-livewire-button 
					wire:click="submitToPublish" 
					wire:confirm="Are you sure to submit your tool for a persistent publication? 

Note: This operation is irreversible and triggers a human to act!"
					loading="Publishing with DOI...">
						Publish with DOI
				</x-livewire-button>
			@endif
		@endif
	@elseif($radar_status==2)
		<p>The tool has been submitted to be persistently published.<br>
		The publishing job is currently uploading the data. This may take a while.</p>
		<p>The curator has been notified. You will be informed per email.</p>
	@elseif($radar_status==3)
		<p>The tool has been submitted to be persistently published.</p>
		<p>The curator has been notified. You will be informed per email.</p>
	@elseif($radar_status==4)
		<p>The tool has been persistently published under the following link: <a href="https://doi.org/{{$doi}}">https://doi.org/{{$doi}}</a>.
		</p>
		<p>Well done!</p>
	@else
		<p>Unknown RADAR status: {{ $radar_status }}. This is a bug, contact the administrators.</p>
		@endif
@endif

@if($status)
	<x-alert title='Info!' color='blue'>{{ $status }}</x-alert>
@endif
@if($warning)
	<x-alert title='Warning!'>{{ $warning }}</x-alert>
@endif
@if($error)
	<x-alert title='Error!'>{{ $error }}</x-alert>
@endif

</div>
