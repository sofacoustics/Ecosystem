<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/3"></th>
			<th class="bg-gray-100">Left ear</th>
			<th class="bg-gray-100">Right ear</th>
		</thead>
		<tbody>
			<tr class="py-2 border">
				<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Time<br><b>Plane:</b> Horizontal</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_1.png')"/>
				</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_2.png')"/>
				</td>
			</tr>
			<tr class="py-2 border">
				@if($counter)
					<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Plane:</b> Median<br><b>Frequency axis:</b> Logarithmic<br>
						<small><button wire:click="minus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
					</td>
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_5.png')"/>
					</td>
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_6.png')"/>
					</td>
				@else
					<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Plane:</b> Median<br><b>Frequency axis:</b> Linear</b><br>
						<small><button wire:click="plus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
					</td>
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_3.png')"/>
					</td>
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_4.png')"/>
					</td>
				@endif
			</tr>
		</tbody>
	</table>

	<p></p>
	<hr>
	<p></p>

	<table class="w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 w-1/2">ITDs in the horizontal plane (in ms)</th>
			<th class="bg-gray-100 w-1/2">Geometry of the available HRTFs</th>
		</thead>
		<tbody>
			<tr>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_7.png')"/>
				</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_8.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
</div>
