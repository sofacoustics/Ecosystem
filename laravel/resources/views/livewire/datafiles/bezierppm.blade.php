<div id="imagecontainer">
	<x-servicelog :log="$this->latestLog"></x-servicelog>
	<script src="/js/stl_viewer/stl_viewer.min.js"></script>
	<div id="stl_cont{{ $datafile->id }}" style="width:500px;height:500px;margin:0 auto; text-align:left;"></div>
	
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

