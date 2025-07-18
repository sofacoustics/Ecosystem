<div id="imagecontainer">
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

