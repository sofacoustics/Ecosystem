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
	<script src="/js/stl_viewer/stl_viewer.min.js"></script>
	
	<div id="stl_cont{{ $datafile->id }}" style="width:500px;height:500px;margin:0 auto; text-align:left;"></div>

	<script>
		var stl_viewer{{ $datafile->id }}=new StlViewer
		(
			document.getElementById("stl_cont{{ $datafile->id }}"),
			{
				models:
				[
					{filename:"{{ $datafile->asset() }}"}
				]
			}
		);
	</script>

</div>

