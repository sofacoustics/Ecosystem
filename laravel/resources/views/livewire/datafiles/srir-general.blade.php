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
		

	<table class="min-w-full border border-gray-300 rounded">
		<thead>
			<th class="bg-gray-100">Measurement</th>
			<th class="bg-gray-100">XY</th>
			<th class="bg-gray-100">XZ</th>
			<th class="bg-gray-100">YZ</th>
			<th class="bg-gray-100">ISO</th>
		</thead>
		<tbody>
		@foreach($postfixes as $postfix)
			<tr>
				<td class="px-6 py-4 whitespace-normal"><b>#{{$loop->index+1}}</b></td>
				<td><x-img class="p-2" :asset="{{ $datafile->asset("",1).'_xy'.$postfix }}" /></td>
				<td><x-img class="p-2" :asset="{{ $datafile->asset("",1).'_xz'.$postfix }}" /></td>
				<td><x-img class="p-2" :asset="{{ $datafile->asset("",1).'_yz'.$postfix }}" /></td>
				<td><x-img class="p-2" :asset="{{ $datafile->asset("",1).'_iso'.$postfix }}" /></td>
			</tr>
		@endforeach
		</tbody>
	</table>
	
</div>
