<div>
<h3>DOI Assignment:</h3>
@if($doi==null)
	<b>Status:</b> No DOI assigned.
	<p>By clicking the button below, a DOI can be assigned to your tool.</p>
	<p>To this end, the following steps will happen:
		<ul class="list-disc list-inside">
			<li>Your tool will be registered at the ÖAW Datathek as a new "dataset".
			<li>The tool metadata will be sent to the Datathek. This is a quick process.
			<li>A DOI for your tool will be requested and assigned to your tool within the Ecosystem.
		</ul>
		<p>Once a DOI has been assigned, the DOI will be a permanent part of your tool.</p>
		<p><b>This operation is irreversible!</b></p>
		  <button wire:click="assignDOI" wire:confirm="Are you sure to assign a DOI to your tool? This operation cannot be reverted!"
					class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
				Assign DOI
			</button>
	<p>Note that this does not publish your tool with that DOI yet: 
		The datafiles will <b>not</b> be transfered to the Datathek yet, and the link between the DOI and your data will be invalid yet.</p>
@else
	<b>Status:</b>
	<ul class="list-disc list-inside">
		<li>DOI assigned: <b>{{ $doi }}</b>.
		<li>When persistently published, the tool will be available under the following link: <b>https://doi.org/{{$doi}}</b>.
	</ul>
@endif
<p></p>
<hr>

<h3>Persistent Publication:</h3>
@if($doi==null)
	<p>If you want to persistently publish your tool, assign a DOI first.</p>
@else
	@if($radarstatus==null || $radarstatus==0)
		<p>By clicking the button below, your tool can be submitted to be persistently published with the DOI.</p>
		<p>To this end, the following steps will be taken:</p>
		<ul class="list-disc list-inside">
			<li>The tool will be set to be locked. No edits will be allowed.
			<li>The metadata at the Datathek will be updated.
			<li>The datafiles will be sent to the Datathek. Depending on the size of your datafiles, this might take a while.
			<li>The curator of the Datathek will be notified.
		</ul>
		<p><b>This operation is irreversible and triggers a human to act!</b></p>
			<button wire:click="submitToPublish" wire:confirm="Are you sure to submit your tool for a persistent publication? This operation cannot be reverted!"
					class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
				Publish with DOI
			</button>
		<p>Once the curator approves your tool, it will be published at the Datathek and the DOI will be valid.</p>
	@elseif($radarstatus==1)
		<p>The tool has been locked and submitted to be persistently published.</p>
		<p>The curator has been notified. Please check later...</p>
	@elseif($radarstatus==2)
		<p>The tool has been persistenly published under the following link: <a href="https://doi.org/{{$doi}}">https://doi.org/{{$doi}}</a>.				
		</p>
		<p>Well done!</p>
	@else
		<p>Unknown RADAR status: {{ $radarstatus }}. This is a bug, contact the administrators.</p>
	@endif
@endif
<p></p>
<hr>
<p>Debugging:</p>
<small>RADAR Status: {{ $radarstatus }}</small>
<x-button wire:click="resetDOI" class="inline">Reset DOI and the Persistent Publication</x-button>
<x-button wire:click="approve" class="inline">Approve the Persistent Publication</x-button>
</div>
