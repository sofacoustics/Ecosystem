<div id="imagecontainer">
	<p><b>Size</b>: {{ $fileSizeInBytes }} bytes 
		@if($fileSizeInKilobytes > 0)
		= {{ $fileSizeInKilobytes }} kbytes 
			@if($fileSizeInMegabytes > 0)
				= {{ $fileSizeInMegabytes }} MB 
				@if($fileSizeInGigabytes)
					= {{ $fileSizeInGigabytes }} GB
				@endif
			@endif
		@endif
		.
		<b>Date created</b>: {{ $created_at }}.
	  <b>Date updated</b>: {{ $updated_at }}.
	</p>
	<script type="text/javascript" src="/js/pannellum/pannellum.js"></script>
	<link rel="stylesheet" href="/js/pannellum/pannellum.css"/>
	
	<div id="panorama{{ $datafile->id }}" style="width:600px;height:400px;"></div>
	<script>
		pannellum.viewer('panorama{{ $datafile->id }}', {
			"type": "equirectangular",
			"panorama": "{{ $datafile->asset('','notime') }}",
			"autoLoad": true
		});
	</script>

</div>

