<div>
<h3>Visibility within the Ecosystem:</h3>
<div class="max-w-prose">
	@if($visible)
		<p><b>Status:</b> The database is <b>visible</b> to all Ecosystem visitors.</p>
		@if($radar_status<2)
			<p>By clicking the button below, the database can be hidden, i.e., it will be visible to you and the admins only.</p>
			<x-livewire-button
				wire:click="hide"
				wire:confirm="Are you sure you want to hide your database?">
				Hide
			</x-livewire-button>
			<p>Note that because the database has been published (i.e., visible) to others,
				your database might be spread across the internet even if you hide it.
			</p>
		@elseif($radar_status<4)
			<p>The database cannot be hidden again because it has been submitted for a persistent publication.</p>
		@else
			<p>The database cannot be hidden again because it has been persistently published.</p>
		@endif
	@else
		<p><b>Status:</b> The database is <b>hidden</b>, i.e., only visible to you and the admins.</p>
		<p>By clicking the button below, the database can be published within the Ecosystem, i.e., visible to all Ecosystem visitors.</p>
		<p>This operation is reversible: The database can be hidden again.
		Still, by exposing the database within the Ecosystem, <b>it is a publication</b>.</p>
		<x-livewire-button
			wire:click="expose" 
			wire:confirm="Are you sure you want to expose your database to others, i.e., publish?">
			Expose
		</x-livewire-button>
		<p>Note that this does not assign a DOI nor publishes the database persistently, see below.</p>
	@endif
</div>
<p></p>
<hr>

<h3>DOI Assignment:</h3>
@if($radar_status==null || $radar_status==0)
	<p><b>DOI Status:</b> No DOI assigned.</p>
	@if($database->metadataValidate())
		<p><b>Metadata Status:</b> Invalid, with following problems:</p>
		<p class="bg-red-100 max-w-full border border-red-400 text-red-700 px-4 py-3 rounded font-bold">{!! nl2br($database->metadataValidate()) !!}</p>
	@else
		<div class="max-w-prose">
			<p><b>Metadata Status:</b> Valid</p>
			<p>By clicking the button below, a DOI can be assigned to your database.</p>
			<p>To this end, the following steps will happen:
				<ul class="list-disc list-inside">
					<li>Your database will be registered at the ÖAW Datathek as a new "Datathek dataset".
					<li>Your metadata will be sent to the Datathek. This is a quick process.
					<li>A DOI for your database will be requested and assigned to your database within the Ecosystem.
				</ul>
			<p>Once a DOI has been assigned, the DOI will be a permanent part of your database.</p>
			<p><b>This operation is irreversible!</b></p>
			<x-livewire-button
				wire:click="assignDOI"
				wire:confirm="Are you sure to assign a DOI to your database? This operation cannot be reverted!"
					loading="Assigning DOI...">
				Assign DOI
			</x-livewire-button>
			<p>Note that this does not publish your database with that DOI yet:
				The datafiles will <b>not</b> be transfered to the Datathek yet, and the link between the DOI and your data will be invalid yet.</p>
			<p>Note also that even with DOI assigned, you will be able to hide your database within the Ecosystem.</p>
		</div>
	@endif
@else
	<b>Status:</b>
	<ul class="list-disc list-inside">
		<li>DOI: Assigned (<b>{{ $doi }}</b>).
		<li class="max-w-prose">DOI link: https://doi.org/{{$doi}}. When persistently published, the database will be available under this link.
		@if($database->metadataValidate())
			<li>Metadata: Invalid, with following problems:<br>
					<p class="bg-red-100 w-full border border-red-400 text-red-700 px-4 py-3 rounded font-bold">{!! nl2br($database->metadataValidate()) !!}</p>
			</li>
		@else
			<li>Metadata: Valid</li>
		@endif
	</ul>
@endif
<p></p>
<hr>

<h3>Persistent Publication:</h3>
<div class="max-w-prose">
	@if($radar_status==null || $radar_status==0)
		<p>If you want to persistently publish your database, assign a DOI first.</p>
	@else
		@if($visible)
			@if($radar_status==1)
				@if($database->metadataValidate())
					<p>If you want to persistently publish your database, provide valid metadata first.</p>
				@else
					<p>By clicking the button below, your database can be submitted to be persistently published with the DOI.</p>
					<p>To this end, the following steps will be taken:</p>
					<ul class="list-disc list-inside">
						<li>The database will be locked. <b>No edits will be allowed!</b>
						<li>The metadata at the Datathek will be updated.
						<li>The datafiles will be sent to the Datathek. Depending on the size of your datafiles, this might take a while.
						<li>The curator of the Datathek will be notified.
					</ul>
					<p><b>This operation is irreversible and triggers a human to act!</b></p>
					<x-livewire-button
						wire:click="submitToPublish"
						wire:confirm="Are you sure to submit your database for a persistent publication? This operation cannot be reverted!"
						loading="Publishing with DOI...">
						Publish with DOI
					</x-livewire-button>
					<p>Once the curator approves your database, it will be published at the Datathek and the DOI will be valid.</p>
				@endif
			@elseif($radar_status==2)
				<p>The database has been submitted for persistent publication.<br>
				The publication job is running and may take a while whilst uploading.<br>
				You will receive an email when publication has been approved.</p>
			@elseif($radar_status==3)
				<p>The database has been submitted for persistent publication.<br>
				The curator has been notified.<br>
				You will receive an email when publication has been approved.</p>
			@elseif($radar_status==4)
				<p>The database has been persistently published under the following link: <a href="https://doi.org/{{$doi}}">https://doi.org/{{$doi}}</a>.
				</p>
				<p>Well done!</p>
			@else
				<p>Unknown RADAR status: {{ $radar_status }}. This is a bug, contact the administrators.</p>
			@endif
		@else
			<p>If you want to persistently publish your database with that DOI, you need to <b>expose</b> the database within the Ecosystem first.</p>
		@endif
	@endif
</div>

@if($status)
	<x-alert title='Info!' color='blue'>{{ $status }}</x-alert>
@endif
@if($warning)
	<x-alert title='Warning!'>{!! $warning !!}</x-alert>
@endif
@if($error)
	<x-alert title='Error!'>{{ $error }}</x-alert>
@endif

</div>
