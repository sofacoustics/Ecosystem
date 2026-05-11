<div>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>

	<script src="/js/stl_viewer/stl_viewer.min.js"></script>

	@if (substr($datafile->name, -4) === '.ply') 
		<p>PLY visualisation is currently not supported.</p>
	@else
		<progress id="progress{{ $datafile->id }}" value="0" max="1" class="w-full bg-gray-200 h-4 rounded-full transition-all duration-500"></progress>
		<div id="stl_cont{{ $datafile->id }}" style="margin: 0 auto; width: 500px; height: 400px; text-align:left;"></div>
	@endif
	<script>
	
		function stl_progress{{ $datafile->id }}(load_status, load_session)
		{
			Object.keys(load_status).forEach(function(model_id) //go over all models to be loaded
			{
				if (load_status[model_id].load_session==load_session) //need to make sure we're on the last loading session (not counting previous loaded models)
				{				
					bar = document.getElementById("progress{{ $datafile->id }}");
					if (load_status[model_id].loaded < load_status[model_id].total)
						bar.value = load_status[model_id].loaded / load_status[model_id].total;
					else
					  bar.style.display = 'none';
				}
			});
		}    
		
		var stl_viewer{{ $datafile->id }}=new StlViewer
		(
			document.getElementById("stl_cont{{ $datafile->id }}"),
			{
				loading_progress_callback:stl_progress{{ $datafile->id }},
				models: [{filename:"{{ $datafile->asset('',true) }}"}],
			}
		);
				
	</script>

</div>

