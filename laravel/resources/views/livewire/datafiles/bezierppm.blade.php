<div id="imagecontainer">
	<script src="/js/stl_viewer/stl_viewer.min.js"></script>
	This widget is not fully implemented yet!
	<div id="stl_cont" style="width:500px;height:500px;margin:0 auto; text-align:left;"></div>

	<script>
		var stl_viewer=new StlViewer
		(
			document.getElementById("stl_cont"),
			{
				models:
				[
					{filename:"{{ $datafile->asset('_1.stl') }}"}
				]
			}
		);
	</script>

</div>

