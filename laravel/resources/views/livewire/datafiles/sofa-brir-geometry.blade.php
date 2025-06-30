<div>
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
		</tbody>
	</table>
	
	<p></p>
	<hr>
	<p></p>

	<table class="w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 ">Geometry of the available BRIRs</th>
		</thead>
		<tbody>
			<tr>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_7.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	
</div>
