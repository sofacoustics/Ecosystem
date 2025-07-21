<div> {{-- component div:START --}}

<h3>Purge the database?</h3>
	@if (count($database->datasets))
		<p>Database {{ $database->title }} currently contains {{ $datasetsCount }} datasets:</p>
		<ul class="list-disc list-inside">
			@forelse($database->datasets as $dataset)
				<li>
					@php
						$nDatafiles = count($dataset->datafiles);
						$nDatasetdefs = count($dataset->database->datasetdefs);
						$nMissing = $nDatasetdefs - $nDatafiles;
					@endphp
					<b>Name</b>: <a href="{{ route('datasets.show', $dataset->id) }}">{{ $dataset->name }}</a>
					@if ($nMissing > 0)
						@if ($nMissing == 1)
							<small>There is 1 datafile missing!</small>
						@elseif($nMissing > 1)
							<small>There are {{ $nMissing }} datafiles missing!</small>
						@endif
					@endif
					@role('admin') <small>(ID: {{ $dataset->id }})</small> @endrole
				</li>
			@empty
				<li>There are no datasets associated with this database</li>
			@endforelse
		</ul>
		<x-livewire-button wire:click="purgeDatabase" loading="Purging...">This will purge the database, i.e., delete ALL datasets!</x-livewire-button>
	@else
		<p>Nothing to purge, there are no datasets associated with this database.</p>
	@endif

</div> {{-- component div:END --}}
