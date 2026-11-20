<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	<b>SOFA Properties:</b>
	<x-sofa-dimensions :csvRows="$csvRows"/>
	
	<div>
		@if($isExpanded==false)
			<div class="flex justify-end">
				<small><button type="button" wire:click="toggleExpand" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white ">Show more SOFA properties...</button></small>
			</div>
		@else
			<div class="flex justify-end">
				<small><button type="button" wire:click="toggleExpand" class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white ">Hide SOFA properties</button></small>
			</div>
			<x-sofa-properties :csvRowsProp="$csvRowsProp"/>
		@endif
	</div>
		
</div>
