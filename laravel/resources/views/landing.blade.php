<x-app-layout>
	<x-slot name="header">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
				Welcome to the SONICOM Ecosystem
			</h2>
			A repository dedicated to spatial hearing and binaural audio!
	</x-slot>
	
	<h3 id="latestTool"><b>Latest Tool:</b> <a href="{{ route('tools.show', $tool->id) }}">{{ $tool->title }} ({{ $tool->productionyear }})</a></h3>
	<ul class="list-disc list-inside inline-block">
		<li><b>Subtitle:</b> {{ $tool->additionaltitle }}
		<li><b>Modified at:</b> {{ $tool->updated_at }} (GMT)
		<li><a href="{{ route('tools.show', $tool->id) }}">More details...</a>
	</ul>
	
	<hr>

	<h3 id="latestDatabase"><b>Most recent Database:</b> <a href="{{ route('databases.show', $database->id) }}">{{ $database->title }} ({{ $database->productionyear }})</a></h3>
	<ul class="list-disc list-inside inline-block">
		<li><b>Subtitle:</b> {{ $database->additionaltitle }}
		<li><b>Modified at:</b> {{ $database->updated_at }} (GMT)
		<li><a href="{{ route('databases.show', $database->id) }}">More details...</a>
	</ul>
	
	<hr>

    @if($datafile)
			<h3 id="latestDatafile"><b>Latest Datafile:</b> <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a></h3>
			<ul class="list-disc list-inside inline-block">
					<li><b>Database:</b> <a href="{{ route('databases.show', $datafile->dataset->database->id) }}">{{ $datafile->dataset->database->title }}</a>
					<li><b>Dataset:</b> <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a>
					<li><b>Datafile Type:</b> {{ $datafile->datasetdef->name }} ({{ $datafile->datasetdef->datafiletype->name }})
					<li><b>Modified at:</b> {{ $datafile->updated_at }} (GMT)
			</ul>
			<div wire:key="{{ $datafile->id }}">
					<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
			</div>
    @endif

</x-app-layout>
