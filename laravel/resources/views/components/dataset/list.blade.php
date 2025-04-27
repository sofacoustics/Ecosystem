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

<b>Name</b>:
@if ($link == 'true')
	<a href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->name }}</a>
@else
	Name: {{ $dataset->name }}
@endif
@if ($datafiles == 'true' && $nMissing > 0)
	@if ($nMissing == 1)
		<small>There is 1 datafile missing!</small>
	@elseif($nMissing > 1)
		<small>There are {{ $nMissing }} datafiles missing!</small>
	@endif
@endif

@role('admin') <small>(ID: {{ $dataset->id }})</small> @endrole
