<div>
<h3>DOI Assignment:</h3>
@if($radarstatus==null || $radarstatus==0)
	<p><b>DOI Status:</b> No DOI assigned.</p>
	@if($tool->metadataValidate())
		<p><b>Metadata Status:</b> Invalid, with following problems:</p>
		<p class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded font-bold">{!! nl2br($tool->metadataValidate()) !!}</p>
	@else
		<p><b>Metadata Status:</b> Valid</p>
		<p>By clicking the button below, a DOI can be assigned to your tool.</p>
		<p>To this end, the following steps will happen:
			<ul class="list-disc list-inside">
				<li>Your tool will be registered at the ÖAW Datathek as a new "dataset".
				<li>The tool metadata will be sent to the Datathek. This is a quick process.
				<li>A DOI for your tool will be requested and assigned to your tool within the Ecosystem.
			</ul>
		<p>Once a DOI has been assigned, the DOI will be a permanent part of your tool.</p>
		<p><b>This operation is irreversible!</b></p>
		<button 
			wire:click="assignDOI" 
			wire:confirm="Are you sure to assign a DOI to your tool? This operation cannot be reverted!"
			wire:loading.attr="disabled"
			wire:loading.class="opacity-50">
			<span wire:loading.remove wire:target="assignDOI"
				class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
				Assign DOI
			</span>
			<span wire:loading wire:target="assignDOI"
				class="bg-gray-500 rounded px-4 py-2 font-bold text-white cursor-not-allowed">
				Processing...
			</span>
		</button>
		<p>Note that this does not publish your tool with that DOI yet:
			The datafiles will <b>not</b> be transfered to the Datathek yet, and the link between the DOI and your data will be invalid yet.</p>
		<p>Note also that even with DOI assigned, you will be able to hide your tool within the Ecosystem.</p>
	@endif
@else
	<b>Status:</b>
	<ul class="list-disc list-inside">
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
@if($radarstatus==null || $radarstatus==0)
	<p>If you want to persistently publish your tool, assign a DOI first.</p>
@else
	@if($radarstatus==1)
		@if($tool->metadataValidate())
			<p>If you want to persistently publish your tool, provide valid metadata first.</p>
		@else
			<p>By clicking the button below, your tool can be submitted to be persistently published with the DOI.</p>
			<p>To this end, the following steps will be taken:</p>
			<ul class="list-disc list-inside">
				<li>The tool will be locked. <b>No edits will be allowed!</b>
				<li>The metadata at the Datathek will be updated.
				<li>The datafiles will be sent to the Datathek. Depending on the size of your datafiles, this might take a while.
				<li>The curator of the Datathek will be notified.
			</ul>
			<p><b>This operation is irreversible and triggers a human to act!</b></p>
				<button wire:click="submitToPublish" 
					wire:confirm="Are you sure to submit your tool for a persistent publication? This operation cannot be reverted!"
					wire:loading.attr="disabled"
					wire:loading.class="opacity-50">
					<span wire:loading.remove wire:target="submitToPublish"
						class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
						Publish with DOI
					</span>
					<span wire:loading wire:target="submitToPublish" 
						class="bg-gray-500 rounded px-4 py-2 font-bold text-white cursor-not-allowed">
						Processing...
					</span>
				</button>
			<p>Once the curator approves your tool, it will be published at the Datathek and the DOI will be valid.</p>
		@endif
	@elseif($radarstatus==2)
		<p>The tool has been submitted to be persistently published.</p>
		<p>The curator has been notified. Please check later...</p>
	@elseif($radarstatus==3)
		<p>The tool has been persistently published under the following link: <a href="https://doi.org/{{$doi}}">https://doi.org/{{$doi}}</a>.
		</p>
		<p>Well done!</p>
	@else
		<p>Unknown RADAR status: {{ $radarstatus }}. This is a bug, contact the administrators.</p>
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
