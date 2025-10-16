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
		
	<div class="flex flex-row">
		<a href="{{ $datafile->asset('_1.png') }}" target="_blank">
			<x-img class="p-2" :asset="$datafile->asset('_1.png')" />
		</a>
	</div>
</div>
