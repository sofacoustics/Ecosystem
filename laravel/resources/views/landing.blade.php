<x-app-layout>
	<x-slot name="header">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight">
					Welcome to SONICOM Ecosystem: A repository dedicated to spatial hearing and binaural audio. 
			</h2>
	</x-slot>
	
	<h3 id="latestTool">Latest Tool:</h3>
	<ul class="list-disc list-inside inline-block">
		<li><b>Title:</b> {{ $tool->title }}
		@if($tool->additionaltitle)
			<li><small><b>Subtitle:</b> {{ $tool->additionaltitle }}</small>
		@endif
		<li><b>Filename:</b> {{ $tool->filename }}
		<li><b>Modified at:</b> {{ $tool->updated_at }}
		<li><a href="{{ route('tools.show', $tool->id) }}">More details...</a>
	</ul>
	
	<hr>
	
	<h3 id="latestDatabase">Most recent Database:</h3> 
	<ul class="list-disc list-inside inline-block">
		<li><b>Title:</b> {{ $database->title }}
		@if($database->additionaltitle)
			<li><small><b>Subtitle:</b> {{ $database->additionaltitle }}</small>
		@endif
		<li><b>Modified at:</b> {{ $database->updated_at }}
		<li><a href="{{ route('databases.show', $database->id) }}">More details...</a>
	</ul>
	
	<hr>
	
	<h3 id="latestDatafile">Latest Datafile:</h3>
	<ul class="list-disc list-inside inline-block">
		<li><b>Database:</b> <a href="{{ route('databases.show', $datafile->dataset->database->id) }}">{{ $datafile->dataset->database->title }}</a>
		<li><b>Dataset:</b> <a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</a>
		<li><b>Datafile Type:</b> {{ $datafile->datasetdef->name }} ({{ $datafile->datasetdef->datafiletype->name }})
		<li><b>Datafile Name:</b> <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a>
		<li><b>Modified at:</b> {{ $datafile->updated_at }}
	</ul>
	<div wire:key="{{ $datafile->id }}">
		<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
	</div>

</x-app-layout>
