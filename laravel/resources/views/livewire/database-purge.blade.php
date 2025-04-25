<div> {{-- component div:START --}}
<h3>Purge the database?</h3>
	@if (count($database->datasets))
		<p>Database {{ $database->title }} currently contains {{ $datasetsCount }} datasets:</p>
		<ul class="list-disc list-inside">
			@forelse($database->datasets as $dataset)
				<li>
					<x-dataset.list link='true' datafiles='true' :dataset="$dataset" />
				</li>
			@empty
				<li>There are no datasets associated with this database</li>
			@endforelse
		</ul>
		<x-button wire:click="purgeDatabase" class="flex items-center gap-1">This will purge the database, i.e., delete ALL datasets!</x-button>
	@else
		<p>Nothing to purge, there are no datasets associated with this database.</p>
	@endif

</div> {{-- component div:END --}}
