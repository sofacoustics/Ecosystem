<div>
	<x-servicelog :log="$latestLog"></x-servicelog>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	<script src="/js/stl_viewer/stl_viewer.min.js"></script>
	<div id="stl_cont{{ $datafile->id }}" style="margin: 0 auto; width: 500px; text-align:left;"></div>
	
	<script>
		var stl_viewer{{ $datafile->id }}=new StlViewer
		(
			document.getElementById("stl_cont{{ $datafile->id }}"),
			{
				models:
				[
					{filename:"{{ $datafile->asset('_1.stl','notimestamp') }}"}
				]
			}
		);
	</script>

</div>

