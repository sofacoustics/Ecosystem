<div>

<p>Database is visible: {{ $visible }}</p>

		  <button wire:click="expose" 
				class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Expose</button>

		  <button wire:click="hide" 
				class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Hide</button>

<p>Database has DOI assigned: {{ $doi }}</p>

		  <button wire:click="doi" 
				class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Assign DOI</button>

<p>Database has been submitted to be published with DOI: Status: {{ $radarstatus }}</p>
		  <button wire:click="publish" 
				class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Publish with DOI</button>
</div>
