<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<div class="flex flex-row">
		<a href="{{ $datafile->asset('_1.png') }}" target="_blank">
			<x-img class="p-2" :asset="$datafile->asset('_1.png')" />
		</a>
	</div>
</div>
