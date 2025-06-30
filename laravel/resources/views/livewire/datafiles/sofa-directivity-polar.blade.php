<div>
	<div class="flex flex-row">
		<a href="{{ $datafile->asset('_1.png') }}" target="_blank">
			<x-img class="p-2" :asset="$datafile->asset('_1.png')" />
		</a>
	</div>
	<button wire:click="plus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">+</button>
	<button wire:click="minus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">-</button>
	{{ $result }}
		
</div>
