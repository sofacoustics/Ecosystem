<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<p>Size: {{ $fileSizeInBytes }} bytes 
		@if($fileSizeInKilobytes > 0)
		= {{ $fileSizeInKilobytes }} kbytes 
			@if($fileSizeInMegabytes > 0)
				= {{ $fileSizeInMegabytes }} MB 
				@if($fileSizeInGigabytes)
					= {{ $fileSizeInGigabytes }} GB
				@endif
			@endif
		@endif
	</p>	
	<p>Date created: {{ $created_at }}</p>
	<p>Date updated: {{ $updated_at }}</p>
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
					<x-img :asset="$datafile->asset('_3.png')"/>
				</td>
			</tr>
		</tbody>
	</table>
	<br>
	
</div>
