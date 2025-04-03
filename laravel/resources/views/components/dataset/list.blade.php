{{--
	A dataset component to display a dataset as an entry in a list

	Parameters:

		$dataset    The dataset we want to display
		$link       'true' if url link should be used, 'false' otherwise
		$datafiles  Display information about the existing/missing datafiles
--}}
@props([
	'link' => 'true',
	'dataset' => null,
	'datafiles' => 'false',
])

@php
	$nDatafiles = count($dataset->datafiles);
	$nDatasetdefs = count($dataset->database->datasetdefs);
	$nMissing = $nDatasetdefs - $nDatafiles;
@endphp

@if ($link == 'true')
	<a href="{{ route('datasets.show', $dataset->id) }}">
@endif
Name: {{ $dataset->name }}
@role('admin')
	(ID: {{ $dataset->id }})
@endrole
@if ($datafiles == 'true' && $nMissing > 0)
	<ul class="list-inside ml-8">
		<li>
			@if ($nMissing == 1)
				There is 1 datafile missing!
			@elseif($nMissing > 1)
				There are {{ $nMissing }} datafiles missing!
			@endif
		</li>
	</ul>
@endif
@if ($link)
	</a>
@endif

@role('admin') <small>(ID: {{ $dataset->id }})</small> @endrole
