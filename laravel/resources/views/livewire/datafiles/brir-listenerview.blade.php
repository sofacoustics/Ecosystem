<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	
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
			<th class="bg-gray-100">Zoom factor</th>
			<th class="bg-gray-100">Left</th>
			<th class="bg-gray-100">Right</th>
		</thead>
		
		@if($postfixes)
			<tbody>
				<tr>
					<td rowspan=2 class="px-6 py-4 whitespace-normal">
						<b>Index of M:</b> 
						<select wire:model.live="counter" class="text-gray-700 mb-2 font-bold" title="Zoom factor">
							@foreach($postfixes as $postfix)
								<option value="{{$loop->index+1}}">{{$loop->index+1}}</option>
							@endforeach
						</select>
					</td>
					<td><x-img class="p-2" :asset="$datafile->asset('',1).'_1'.$postfixes[$counter-1]" /></td>
					<td><x-img class="p-2" :asset="$datafile->asset('',1).'_2'.$postfixes[$counter-1]" /></td>
			</tbody>
		@else
			<tbody><tr><td>No plots found</td></tr></tbody>
		@endif
	</table>
	
</div>
