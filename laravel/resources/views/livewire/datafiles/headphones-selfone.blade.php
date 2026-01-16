<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	<b>SOFA Properties:</b>
	<x-sofa-dimensions :csvRows="$csvRows"/>
	
	<div wire:click="toggleExpand">
		@if($isExpanded==false)
			<div class="flex justify-end">
			<small><button class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white ">Show more SOFA properties...</button></small>
			</div>
		@else
			<x-sofa-properties :csvRowsProp="$csvRowsProp"/>
		@endif
	</div>
		
	<p></p>
	<hr>
	<p></p>
	
	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6">E</th>
			<th class="bg-gray-100">Spectrum, Effect of R: M=1, E varies</th>
		</thead>
		@if($spectrumEs)
			<tbody>
				<tr class="py-2 border">
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
						<select wire:model.live="counter" class="text-gray-700 mb-2 font-bold" title="Measurement index">
							@foreach($spectrumEs as $E)
								<option value="{{$E}}">{{$E}}</option>
							@endforeach
						</select>
					</td>
					<td class="text-center align-middle">
						<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_E='.$E.'.png'" />
					</td>
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plots found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6">R</th>
			<th class="bg-gray-100">Spectrum, Effect of E: M=1, R varies</th>
		</thead>
		@if($spectrumRs)
			<tbody>
				<tr class="py-2 border">
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
						<select wire:model.live="counter" class="text-gray-700 mb-2 font-bold" title="Measurement index">
							@foreach($spectrumRs as $R)
								<option value="{{$R}}">{{$R}}</option>
							@endforeach
						</select>
					</td>
					<td class="text-center align-middle">
						<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_R='.$R.'.png'" />
					</td>
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plot found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6">E</th>
			<th class="bg-gray-100">Energy, Effect of R: M=1, E varies</th>
		</thead>
		@if($energyEs)
			<tbody>
				<tr class="py-2 border">
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
						<select wire:model.live="counter" class="text-gray-700 mb-2 font-bold" title="Measurement index">
							@foreach($energyEs as $E)
								<option value="{{$E}}">{{$E}}</option>
							@endforeach
						</select>
					</td>
					<td class="text-center align-middle">
						<x-img class="p-2" :asset="$datafile->asset('',1).'_energy_M=1_E='.$E.'.png'" />
					</td>
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plots found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100">Geometry</th>
		</thead>
		<tbody>
			<tr class="py-2 border">
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_geometry.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
</div>
