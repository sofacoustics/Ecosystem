<div>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>


	@if (substr($datafile->name, -4) === '.ply') 
		<style>
			#ply-viewer-container {
				width: 100%;
				height: 600px; /* Specify the height here, otherwise it may end up being 0! */
				min-height: 400px; /* ensure it doesn't collapse */
			}
		</style>
		<div 
			id="ply-viewer-container" 
			data-model="{{ $datafile->asset('') }}">
		</div>
		{{-- 
			This pushes the Vite directive into the @stack('scripts') location
			in your master layout. This script will ONLY be loaded on this page.
		--}}
		@push('scripts')
			@vite('resources/js/ply-viewer.js')
		@endpush

	@else
		<script src="/js/stl_viewer/stl_viewer.min.js"></script>
		<progress id="progress{{ $datafile->id }}" value="0" max="1" class="w-full bg-gray-200 h-4 rounded-full transition-all duration-500"></progress>
		<div id="stl_cont{{ $datafile->id }}" style="margin: 0 auto; width: 500px; height: 400px; text-align:left;"></div>
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
	@endif

</div>

