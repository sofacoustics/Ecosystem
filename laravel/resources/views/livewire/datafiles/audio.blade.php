<div>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	<audio controls>
		<source src="{{ $datafile->asset() }}">
			Your browser does not support the audio element.
	</audio>
</div>

