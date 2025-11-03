<div>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	@hasrole('admin')
		<p>Internal path: {{ $fullPath }}</p>
		<p>RADAR ID: {{ $radar_id }}</p>
		<p>RADAR Datasetdef ID: {{ $datasetdef_radar_id }}</p>
		<p>RADAR Datasetdef Upload URL: {{ $datasetdef_radar_upload_url }}</p>
	@endhasrole
</div>
