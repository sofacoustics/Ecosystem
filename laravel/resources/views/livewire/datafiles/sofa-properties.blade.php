<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	
	<x-sofa-dimensions :csvRows="$csvRows"/>
	
	<div class="expandable-box" wire:click="toggleExpand">
		@if($isExpanded==false)
			<div class="box-content collapsed-preview">
				<small><button class="bg-blue-500 hover:bg-blue-700 rounded px-4 py-2 font-bold text-white">Show more SOFA properties...</button></small>
			</div>
		@else
			<x-sofa-properties :csvRowsProp="$csvRowsProp"/>
		@endif
	</div>
	<br>
</div>
