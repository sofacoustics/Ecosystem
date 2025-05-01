<div>
	<p>Internal path: {{ $fullPath }}</p>
	<p>Size: {{ $fileSizeInBytes }} bytes 
		@if($fileSizeInKilobytes > 0)
		= {{ $fileSizeInKilobytes }} kbytes 
			@if($fileSizeInMegabytes > 0)
				= {{ $fileSizeInMegabytes }} MB 
				@if($fileSizeInGigabytes)
					= {{ $fileSizeInGigabytes }} GB
				@endif
			@endif
		@endif
	</p>
	<p>Date created: {{ $created_at }}</p>
	<p>Date updated: {{ $updated_at }}</p>
</div>