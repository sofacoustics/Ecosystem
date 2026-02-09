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
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100 max-w-1/6">E</th>
			<th class="bg-gray-100">Spectrum, Effect of R: M=1, E varies</th>
		</thead>
		@if($spectrumEs)
					<tbody>
				<tr class="py-2 border">
					@if($counter)
						<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Logarithmic<br> 
							<small><button wire:click="minus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
						</td>
						<td class="px-6 py-4 whitespace-normal text-center align-middle">
							<select wire:model.live="selectorES" class="text-gray-700 mb-2 font-bold" title="Emitter index">
								@foreach($spectrumEs as $E)
									<option value="{{$E}}">{{$E}}</option>
								@endforeach
							</select>
						</td>	
						<td class="text-center align-middle py-2">
							<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_E='.$selectorES.'_logX.png'" />
						</td>
					@else
						<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Linear<br> 
							<small><button wire:click="plus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
						</td>
						<td class="px-6 py-4 whitespace-normal text-center align-middle">
							<select wire:model.live="selectorES" class="text-gray-700 mb-2 font-bold" title="Emitter index">
								@foreach($spectrumEs as $E)
									<option value="{{$E}}">{{$E}}</option>
								@endforeach
							</select>
						</td>	
						<td class="text-center align-middle py-2">
							<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_E='.$selectorES.'_linX.png'" />
						</td>
					@endif
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plots found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100 max-w-1/6">R</th>
			<th class="bg-gray-100">Spectrum, Effect of E: M=1, R varies</th>
		</thead>
		@if($spectrumRs)
			<tbody>
				<tr class="py-2 border">
					@if($counter)
						<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Logarithmic<br> 
							<small><button wire:click="minus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
						</td>
						<td class="px-6 py-4 whitespace-normal text-center align-middle">
							<select wire:model.live="selectorRS" class="text-gray-700 mb-2 font-bold" title="Receiver index">
								@foreach($spectrumRs as $R)
									<option value="{{$R}}">{{$R}}</option>
								@endforeach
							</select>
						</td>	
						<td class="text-center align-middle py-2">
							<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_R='.$selectorRS.'_logX.png'" />
						</td>
					@else
						<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Linear<br> 
							<small><button wire:click="plus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
						</td>
						<td class="px-6 py-4 whitespace-normal text-center align-middle">
							<select wire:model.live="selectorRS" class="text-gray-700 mb-2 font-bold" title="Receiver index">
								@foreach($spectrumRs as $R)
									<option value="{{$R}}">{{$R}}</option>
								@endforeach
							</select>
						</td>	
						<td class="text-center align-middle py-2">
							<x-img class="p-2" :asset="$datafile->asset('',1).'_spectrum_M=1_R='.$selectorRS.'_linX.png'" />
						</td>
					@endif
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plot found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100">Spectrum, Effect of M: R=[1(in-ear), 4(F3)], E=12(13)</th>
		</thead>
		<tbody>
			<tr class="py-2 border">
				@if($counter)
					<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Logarithmic<br> 
						<small><button wire:click="minus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
					</td>
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
					</td>	
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_spectrum_E=12_R=1_logX.png')"/>
					</td>
				@else
					<td class="px-6 py-4 whitespace-normal"><b>Domain:</b> Amplitude spectral<br><b>Note:</b> Original IRs of length N=256 samples were zero-padded to 16*N samples.<br><b>Frequency axis:</b> Linear<br> 
						<small><button wire:click="plus" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Change axis</button></small>
					</td>
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
					</td>	
					<td class="text-center align-middle py-2">
						<x-img :asset="$datafile->asset('_spectrum_E=12_R=1_linX.png')"/>
					</td>
				@endif
			</tr>
		</tbody>
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100 max-w-1/6">E</th>
			<th class="bg-gray-100">Energy, Effect of R: M=1, E varies</th>
		</thead>
		@if($energyEs)
			<tbody>
				<tr class="py-2 border">
					<td class="px-6 py-4 whitespace-normal"><b>Coordinates:</b> Cartesian<br><b>Note:</b> Sum of squared IRs at receivers (coloured dots) when one emitter is active (blue cross).<br> 
					</td>
					<td class="px-6 py-4 whitespace-normal text-center align-middle">
						<select wire:model.live="selectorEE" class="text-gray-700 mb-2 font-bold" title="Emitter index">
							@foreach($energyEs as $E)
								<option value="{{$E}}">{{$E}}</option>
							@endforeach
						</select>
					</td>
					<td class="text-center align-middle">
						<x-img class="p-2" :asset="$datafile->asset('',1).'_energy_M=1_E='.$selectorEE.'.png'" />
					</td>
				</tr>
			</tbody>
		@else
			<tbody><tr><td>No plots found</td></tr></tbody>
		@endif
	</table>

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100 max-w-1/6"></th>
			<th class="bg-gray-100">Geometry</th>
		</thead>
		<tbody>
			<tr class="py-2 border">
				<td class="px-6 py-4 whitespace-normal"><b>Coordinates:</b> Cartesian<br><b>Note:</b> Position of in-ear microphone unknown.<br> 
				</td>
				<td class="text-center align-middle">
					<x-img :asset="$datafile->asset('_geometry.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
</div>
