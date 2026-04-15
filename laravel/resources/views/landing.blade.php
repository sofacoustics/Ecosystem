<x-app-layout>
	<x-slot name="header">
			<h2 class="font-semibold text-xl text-gray-800 leading-tight text-center align-middle">
				Welcome to the SONICOM Ecosystem
			</h2>
			<div class="text-center align-middle">
				A repository dedicated to spatial hearing and binaural audio!
			</div>
	</x-slot>
	
	<p class="text-center align-middle max-w-[900px]">
		The SONICOM Ecosystem provides an interactive repository for spatial auditory data closely integrated with tools for binaural rendering and auditory modeling.
		Its content is open access and can be cited via persistent URLs and DOIs. It also provides a programmatic interface for downloading data subsets, reinforcing the idea of reproducible research.
		Its commenting functionality enables researchers and end users to provide feedback easily. The connection of databases with tools and external resources renders it a true <b>Ecosystem</b> for the spatial-hearing community.
		<a href="/about">More information...</a>
	</p>
	<br>
	<table class="w-full border">
		<tbody>
			<tr class="py-2 max-w-1/3">
				<td class="border-r border-gray-300 p-2 text-center align-middle">
					<x-button class="inline" method="GET" action="{{ route('databases.index') }}">Browse databases</x-button>
				</td>
				<td class="text-center align-middle">
					<x-button class="inline" method="GET" action="{{ route('tools.index') }}">Browse tools</x-button>
				</td>
			</tr>
			<tr>
				<td class="border-r border-gray-300 px-6 py-2 text-center align-middle">
					A database is a collection of data stored in a structured way. A database consists of datasets, all of which have the same structure specified by the dataset definition.
				</td>
				<td class="text-center align-middle px-6 py-2">
					Tools are files which do not need the structure of a database. They can be of the category software, model, text, physical object, or something else. 
				</td>
			</tr>
			
			<tr>
				<td class="border-r border-gray-300 p-2 text-center align-middle">
					Latest database: <a href="{{ route('databases.show', $database->id) }}"><b>{{ $database->title }} ({{ $database->productionyear }})</b></a>
				</td>
				<td class="text-center align-middle">
					Latest tool: <a href="{{ route('tools.show', $tool->id) }}"><b>{{ $tool->title }} ({{ $tool->productionyear }})</b></a>
				</td>
			</tr>
		</tbody>
	</table>

	<br>
	
	<div id="funding" style="display: flex; align-items: center; justify-content: center; gap: 20px; padding: 10px 0; max-width: 900px; margin-left: auto; margin-right: auto;">
		<a href="https://www.oeaw.ac.at/en" target="_blank"><img src="images/oeaw-logo.png" alt="ÖAW logo" style="height: 60px; width: auto; flex-shrink: 0;"></img></a>
		<p style="margin: 0; text-align: center; flex: 1;">
			The SONICOM Ecosystem is funded by the <a href="https://www.oeaw.ac.at/en" target="_blank">Austrian Academy of Sciences</a> and has received funding from the European Union’s Horizon 2020 research and innovation programme (grant agreement <a href="https://doi.org/10.3030/101017743" target="_blank">#101017743</a>).
		</p>
		<a href="https://commission.europa.eu/index_en" target="_blank"><img src="images/eu-flag.png" alt="EU logo" style="height: 60px; width: auto; flex-shrink: 0;"></img></a>
	</div>
	
	<br><hr><br>
	
	@if($datafile)
		<p>
			<b>Most recently contributed datafile: <a href="{{ route('datafiles.show', $datafile->id) }}">{{ $datafile->name }}</a></b>
		</p>
		<ul class="list-disc list-outside px-5 inline-block">
			<li>Belongs to the database: <b><a href="{{ route('databases.show', $datafile->dataset->database->id) }}">
				{{ $datafile->dataset->database->title }}  ({{ $database->productionyear }})</b></a>
			<li>Belongs to the dataset: <b><a href="{{ route('datasets.show', $datafile->dataset->id) }}">{{ $datafile->dataset->name }}</b></a>
		</ul>
		<div wire:key="{{ $datafile->id }}">
				<livewire:DatafileListener :datafile="$datafile" :key="$datafile->id" />
		</div>
	@endif

</x-app-layout>
