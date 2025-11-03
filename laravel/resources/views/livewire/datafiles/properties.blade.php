<div>
	<p><b>Size</b>: {{ $fileSizeInBytes }} bytes
		@if($fileSizeInBytes > 0)
			@if($fileSizeInKilobytes > 0)
			= {{ $fileSizeInKilobytes }} kbytes 
				@if($fileSizeInMegabytes > 0)
					= {{ $fileSizeInMegabytes }} MB 
					@if($fileSizeInGigabytes)
						= {{ $fileSizeInGigabytes }} GB
					@endif
				@endif
			@endif
		@else
			<x-error>The datafile is missing from disk!</x-error>
		@endif
		.
		<b>Date created</b>: {{ $created_at }}.
	  <b>Date updated</b>: {{ $updated_at }}.
	</p>	

	@hasrole('admin')
		<p>Internal path: {{ $fullPath }}</p>
		<p>RADAR ID: {{ $radar_id }}</p>
		<p>RADAR Datasetdef ID: {{ $datasetdef_radar_id }}</p>
		<p>RADAR Datasetdef Upload URL: {{ $datasetdef_radar_upload_url }}</p>
	@endhasrole
</div>
