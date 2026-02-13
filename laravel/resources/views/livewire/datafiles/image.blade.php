<div>
	<p><x-datafiles-properties :fileSizeInBytes="$fileSizeInBytes" :createdAt="$created_at" :updatedAt="$updated_at"/></p>
	
	<div id="imageContainer{{ $datafile->id }}" style="margin: 0 auto; width: 500px; height: 400px; background-color: #FFFFFF !important;">
		<img id="image{{ $datafile->id }}" src="{{ $datafile->asset() }}" alt="{{ $datafile->name }}" style="display: none;">
	</div>

</div>

<script>
	document.addEventListener('DOMContentLoaded', () => {
		const image{{ $datafile->id }} = document.getElementById('image{{ $datafile->id }}');
		const viewer{{ $datafile->id }} = new Viewer(image{{ $datafile->id }}, {
			inline: true,          // Shows inside the container immediately
			navbar: false,        // Hides the thumbnail bar (not needed for 1 image)
			title: 3,         // Hides the image title
			toolbar: {
				zoomIn: 1,          // 1 = visible
				zoomOut: 1,
				oneToOne: 1,
				reset: 1,
				rotateLeft: 1,
				rotateRight: 1,
				flipHorizontal: false,
				flipVertical: false,
				initialCoverage: 1,
			},
			viewed() { viewer{{ $datafile->id }}.reset(); },
			loading: true,
			movable: true,
		});
	});
	
</script>