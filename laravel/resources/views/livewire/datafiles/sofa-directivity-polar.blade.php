<div>
	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/4"></th>
			<th class="bg-gray-100">Polar pattern</th>
			<th class="bg-gray-100">Geometry</th>
		</thead>
		<tbody>
			<tr class="py-2 border">
				<td class="px-6 py-4 whitespace-normal"><b>Frequency:</b> 
					<select wire:model="result">
						<option value="0">31.5 Hz</option>
						<option value="1">63 Hz</option>
						<option value="2">125 Hz</option>
						<option value="3">250 Hz</option>
						<option value="4">500 Hz</option>
						<option value="5" selected>1 kHz</option>
						<option value="6">2 kHz</option>
						<option value="7">4 kHz</option>
						<option value="8">8 kHz</option>
						<option value="9">16 kHz</option>
					</select>
				</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_2_'.$result.'.png')"/>
				</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_1.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
</div>
