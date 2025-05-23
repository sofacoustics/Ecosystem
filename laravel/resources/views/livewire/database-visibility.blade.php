<div>
<h3>Visibility within the Ecosystem:</h3>
@if($visible)
    <p><b>Status:</b> The database is <b>visible</b> to all Ecosystem visitors.</p>
    @if($radarstatus<1)
        <p>By clicking the button below, the database can be hidden, i.e., it will be visible to you and the admins only.</p>
        <button wire:click="hide" wire:confirm="Are you sure to hide your database?"
                class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
            Hide
        </button>
        <p>Note that because the database has been published (i.e., visible) to others, 
            your database might be spread across the internet even if you hide it.
        </p>
    @endif
@else
    <p><b>Status:</b> The database is <b>hidden</b>, i.e., only visible to you and the admins.</p>
    <p>By clicking the button below, the database can be published within the Ecosystem, i.e., visible to all Ecosystem visitors.</p>
    <p>This operation is reversible: The database can be hidden again. 
    Still, by exposing the database within the Ecosystem, <b>it is a publication</b>.</p>
    <button wire:click="expose" wire:confirm="Are you sure to expose your database to others, i.e., publish?"
            class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
        Expose
    </button>
    <p>Note that this does not assign a DOI nor publishes the database persistently, see below.</p>
@endif
<p></p>
<hr>

<h3>DOI Assignment:</h3>
@if($doi==null)
    <b>Status:</b> No DOI assigned.
    <p>By clicking the button below, a DOI can be assigned to your database.</p>
    <p>To this end, the following steps will happen:
        <ul class="list-disc list-inside">
            <li>Your database will be registered at the ÖAW Datathek as a new "dataset".
            <li>The database metadata will be sent to the Datathek. This is a quick process.
            <li>A DOI for your database will be requested and assigned to your database within the Ecosystem.
        </ul>
        <p>Once a DOI has been assigned, the DOI will be a permanent part of your database.</p>
        <p><b>This operation is irreversible!</b></p>
          <button wire:click="assignDOI" wire:confirm="Are you sure to assign a DOI to your database? This operation cannot be reverted!"
                    class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
                Assign DOI
            </button>
			@if($status)
				<x-alert title='Info!' color='blue'>{{ $status }}</x-alert>
			@endif
			@if($warning)
				<x-alert title='Warning!'>{{ $warning }}</x-alert>
			@endif
			@if($error)
				<x-alert title='Error!'>{{ $error }}</x-alert>
			@endif
    <p>Note that this does not publish your database with that DOI yet:
        The datafiles will <b>not</b> be transfered to the Datathek yet, and the link between the DOI and your data will be invalid yet.</p>
    <p>Note also that even with DOI assigned, you will be able to hide your database within the Ecosystem.</p>
@else
    <b>Status:</b>
    <ul class="list-disc list-inside">
        <li>DOI assigned: <b>{{ $doi }}</b>.
        <li>When persistently published, the database will be available under the following link: <b>https://doi.org/{{$doi}}</b>.
    </ul>
@endif
<p></p>
<hr>

<h3>Persistent Publication:</h3>
@if($doi==null)
    <p>If you want to persistently publish your database, assign a DOI first.</p>
@else
    @if($visible)
        @if($radarstatus==null || $radarstatus==0)
            <p>By clicking the button below, your database can be submitted to be persistently published with the DOI.</p>
            <p>To this end, the following steps will be taken:</p>
            <ul class="list-disc list-inside">
                <li>The database will be set to be locked. No edits will be allowed.
                <li>The metadata at the Datathek will be updated.
                <li>The datafiles will be sent to the Datathek. Depending on the size of your datafiles, this might take a while.
                <li>The curator of the Datathek will be notified.
            </ul>
            <p><b>This operation is irreversible and triggers a human to act!</b></p>
                <button wire:click="submitToPublish" wire:confirm="Are you sure to submit your database for a persistent publication? This operation cannot be reverted!"
                        class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">
                    Publish with DOI
                </button>
            <p>Once the curator approves your database, it will be published at the Datathek and the DOI will be valid.</p>
        @elseif($radarstatus==1)
            <p>The database has been locked and submitted to be persistently published.</p>
            <p>The curator has been notified. Please check later...</p>
        @elseif($radarstatus==2)
            <p>The database has been persistenly published under the following link: <a href="https://doi.org/{{$doi}}">https://doi.org/{{$doi}}</a>.				
            </p>
            <p>Well done!</p>
        @else
            <p>Unknown RADAR status: {{ $radarstatus }}. This is a bug, contact the administrators.</p>
        @endif
    @else
        <p>If you want to persistently publish your database with that DOI, you need to <b>expose</b> the database within the Ecosystem first.</p>
    @endif
@endif
<p></p>
<hr>
<p>Debugging:</p>
<small>RADAR Status: {{ $radarstatus }}</small>
<x-button wire:click="resetDOI" class="inline">Reset DOI and the Persistent Publication</x-button>
<x-button wire:click="approve" class="inline">Approve the Persistent Publication</x-button>
</div>
